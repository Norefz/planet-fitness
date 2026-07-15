<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ZoomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private ZoomService $zoom) {}

    // ── Index ──────────────────────────────────────────────────────────────
    public function index(): View
    {
        $mentor = Auth::user()->mentor;

        $pending = $mentor->bookings()
            ->pending()
            ->with('member')
            ->orderBy('scheduled_at')
            ->get();

        $confirmed = $mentor->bookings()
            ->confirmed()
            ->with('member')
            ->orderBy('scheduled_at')
            ->get();

        $history = $mentor->bookings()
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('member')
            ->latest('scheduled_at')
            ->take(10)
            ->get();

        return view('mentor.bookings.index', compact('pending', 'confirmed', 'history'));
    }

    // ── Update ─────────────────────────────────────────────────────────────
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless(
            $booking->mentor_id === Auth::user()->mentor->id,
            403,
            'Kamu tidak memiliki akses ke jadwal ini.'
        );

        $validated = $request->validate([
            'action'       => ['required', 'in:confirm,cancel,complete,notes'],
            'mentor_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        switch ($validated['action']) {

            // ── Konfirmasi: buat Zoom meeting ─────────────────────────────
            case 'confirm':
                try {
                    $meeting = $this->zoom->createMeeting(
                        topic:     $booking->topic ?? 'Konsultasi Planet Fitness',
                        startTime: $booking->scheduled_at->format('Y-m-d\TH:i:s'),
                        duration:  $booking->duration_minutes,
                        timezone:  'Asia/Jakarta',
                    );

                    $booking->update([
                        'status'          => 'confirmed',
                        'meeting_url'     => $meeting['join_url'],    // untuk member
                        'zoom_meeting_id' => $meeting['meeting_id'],
                        'zoom_start_url'  => $meeting['start_url'],   // untuk mentor
                    ]);

                    $message = 'Booking dikonfirmasi. Tautan Zoom telah dibuat.';

                } catch (\Throwable $e) {
                    // Kalau Zoom API gagal, tetap konfirmasi tapi tanpa link
                    Log::error('Zoom API error saat konfirmasi booking', [
                        'booking_id' => $booking->id,
                        'error'      => $e->getMessage(),
                    ]);

                    $booking->update(['status' => 'confirmed']);

                    $message = 'Booking dikonfirmasi, tapi tautan Zoom gagal dibuat. Silakan buat manual.';
                }
                break;

            // ── Batalkan: hapus Zoom meeting jika ada ────────────────────
            // ── Batalkan: hapus Zoom meeting jika ada ────────────────────
            case 'cancel':
                // Validasi bahwa alasan pembatalan wajib diisi dari sisi mentor
                $request->validate([
                    'cancellation_reason' => ['required', 'string', 'max:500'],
                ]);

                if ($booking->zoom_meeting_id) {
                    try {
                        $this->zoom->deleteMeeting($booking->zoom_meeting_id);
                    } catch (\Throwable $e) {
                        Log::warning('Gagal hapus Zoom meeting', [
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
                    'cancellation_reason' => $request->input('cancellation_reason'), // Simpan alasan
                ]);
                $message = 'Booking berhasil dibatalkan beserta alasan.';
                break;

            // ── Tandai selesai ────────────────────────────────────────────
            case 'complete':
                $booking->update(['status' => 'completed']);
                $message = 'Sesi ditandai selesai.';
                break;

            // ── Simpan catatan sesi ───────────────────────────────────────
            case 'notes':
                $booking->update(['mentor_notes' => $validated['mentor_notes'] ?? null]);
                $message = 'Catatan sesi disimpan.';
                break;
        }

        return redirect()->route('mentor.bookings.index')->with('success', $message);
    }
}
