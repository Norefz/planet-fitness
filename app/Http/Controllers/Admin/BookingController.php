<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private ZoomService $zoom)
    {
    }

    /**
     * Daftar booking konsultasi — tabel + kalender mini + jadwal hari ini
     * (mengikuti desain admin-booking.html).
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $q      = trim((string) $request->query('q', ''));
        $month  = $request->query('month'); // format Y-m, mis. "2026-07"
        $date   = $request->query('date'); // format Y-m-d, mis. "2026-07-16" — filter dari klik kalender

        $calendarMonth = $month ? Carbon::createFromFormat('Y-m', $month)->startOfMonth() : now()->startOfMonth();

        // Jika tanggal dipilih tapi bukan dari bulan kalender yang sedang tampil,
        // ikuti bulan dari tanggal tsb supaya kalender & filter tetap sinkron.
        if ($date) {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
                if (! $month) {
                    $calendarMonth = $selectedDate->copy()->startOfMonth();
                }
            } catch (\Throwable $e) {
                $date = null;
            }
        }

        $query = Booking::with(['member', 'mentor']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('member', fn ($m) => $m->where('full_name', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$q}%")))
                    ->orWhereHas('mentor', fn ($m) => $m->where('full_name', 'like', "%{$q}%"));
            });
        }

        if ($date) {
            $query->whereDate('scheduled_at', $date);
        }

        $bookings = $query->latest('scheduled_at')->paginate(10)->withQueryString();

        // ── Statistik ────────────────────────────────────────────────────
        $stats = [
            'pending'             => Booking::where('status', 'pending')->count(),
            'confirmed_this_week' => Booking::where('status', 'confirmed')
                ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'today'               => Booking::whereIn('status', ['pending', 'confirmed'])
                ->whereDate('scheduled_at', today())->count(),
            'cancelled_this_month' => Booking::where('status', 'cancelled')
                ->whereMonth('scheduled_at', now()->month)->whereYear('scheduled_at', now()->year)->count(),
        ];

        // ── Kalender mini: tanggal dalam bulan ini yang punya booking ────
        $bookingDates = Booking::whereBetween('scheduled_at', [$calendarMonth->copy()->startOfMonth(), $calendarMonth->copy()->endOfMonth()])
            ->get()
            ->map(fn ($b) => $b->scheduled_at->format('Y-m-d'))
            ->unique()
            ->flip();

        // ── Jadwal hari ini ───────────────────────────────────────────────
        $todaySchedule = Booking::with(['member', 'mentor'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('scheduled_at', today())
            ->orderBy('scheduled_at')
            ->get();

        return view('admin.bookings.index', compact(
            'bookings', 'stats', 'status', 'q', 'date', 'calendarMonth', 'bookingDates', 'todaySchedule'
        ));
    }

    /**
     * Konfirmasi booking — buat Zoom meeting (pola sama seperti sisi Mentor).
     */
    public function confirm(Booking $booking): RedirectResponse
    {
        try {
            $meeting = $this->zoom->createMeeting(
                topic:     $booking->topic ?? 'Konsultasi Planet Fitness',
                startTime: $booking->scheduled_at->format('Y-m-d\TH:i:s'),
                duration:  $booking->duration_minutes,
                timezone:  'Asia/Jakarta',
            );

            $booking->update([
                'status'          => 'confirmed',
                'meeting_url'     => $meeting['join_url'],
                'zoom_meeting_id' => $meeting['meeting_id'],
                'zoom_start_url'  => $meeting['start_url'],
            ]);

            $message = 'Booking dikonfirmasi. Tautan Zoom telah dibuat.';
        } catch (\Throwable $e) {
            Log::error('Zoom API error saat admin konfirmasi booking', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
            ]);

            $booking->update(['status' => 'confirmed']);

            $message = 'Booking dikonfirmasi, tapi tautan Zoom gagal dibuat. Mentor perlu membuatnya manual.';
        }

        AuditLog::record(
            action: 'booking_confirm',
            details: "Booking konsultasi <strong>{$booking->member->full_name}</strong> × <strong>{$booking->mentor->full_name}</strong> dikonfirmasi oleh admin.",
            targetTable: 'bookings',
            targetId: $booking->id,
        );

        return redirect()->back()->with('success', $message);
    }

    /**
     * Batalkan booking — hapus Zoom meeting jika ada (pola sama seperti sisi Mentor).
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $reason = $request->input('cancellation_reason') ?: 'Dibatalkan oleh admin.';

        if ($booking->zoom_meeting_id) {
            try {
                $this->zoom->deleteMeeting($booking->zoom_meeting_id);
            } catch (\Throwable $e) {
                Log::warning('Gagal hapus Zoom meeting saat admin membatalkan booking', [
                    'meeting_id' => $booking->zoom_meeting_id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        $booking->update([
            'status'              => 'cancelled',
            'meeting_url'         => null,
            'zoom_meeting_id'     => null,
            'zoom_start_url'      => null,
            'cancellation_reason' => $reason,
        ]);

        AuditLog::record(
            action: 'booking_cancel',
            details: "Booking konsultasi <strong>{$booking->member->full_name}</strong> × <strong>{$booking->mentor->full_name}</strong> dibatalkan oleh admin.",
            targetTable: 'bookings',
            targetId: $booking->id,
        );

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
