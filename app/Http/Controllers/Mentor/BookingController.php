<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $mentor = Auth::user()->mentor;

        $pending = $mentor->bookings()->pending()->with('member')->orderBy('scheduled_at')->get();

        $confirmed = $mentor->bookings()->confirmed()->with('member')->orderBy('scheduled_at')->get();

        $history = $mentor->bookings()
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('member')
            ->latest('scheduled_at')
            ->take(10)
            ->get();

        return view('mentor.bookings.index', compact('pending', 'confirmed', 'history'));
    }

    /**
     * UPDATE — konfirmasi, tolak, tandai selesai, atau simpan catatan sesi.
     */
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->mentor_id === Auth::user()->mentor->id, 403, 'Kamu tidak memiliki akses ke jadwal ini.');

        $validated = $request->validate([
            'action'       => ['required', 'in:confirm,cancel,complete,notes'],
            'mentor_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        switch ($validated['action']) {
            case 'confirm':
                // Pada implementasi produksi, URL ini didapat dari respons Zoom API
                // saat status berubah menjadi confirmed (lihat SAD bagian 4.1 & 5.4).
                $booking->update([
                    'status'      => 'confirmed',
                    'meeting_url' => $booking->meeting_url ?? 'https://zoom.us/j/' . Str::random(10),
                ]);
                $message = 'Booking dikonfirmasi. Tautan Zoom telah dibuat.';
                break;

            case 'cancel':
                $booking->update(['status' => 'cancelled']);
                $message = 'Booking dibatalkan.';
                break;

            case 'complete':
                $booking->update(['status' => 'completed']);
                $message = 'Sesi ditandai selesai.';
                break;

            case 'notes':
                $booking->update(['mentor_notes' => $validated['mentor_notes'] ?? null]);
                $message = 'Catatan sesi disimpan.';
                break;
        }

        return redirect()->route('mentor.bookings.index')->with('success', $message);
    }
}
