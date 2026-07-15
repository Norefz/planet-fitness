<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    // ── Tampilkan halaman konsultasi member ───────────────────────────────
    public function index(): View
    {
        $mentors = Mentor::where('is_verified', true)
            ->with('user')
            ->get();

        $member = Auth::user()->member;

        // Booking member yang masih aktif (pending & confirmed)
        $myBookings = $member->bookings()
            ->with('mentor')
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('scheduled_at')
            ->get();

        // Riwayat booking (selesai & dibatalkan)
        $myHistory = $member->bookings()
            ->with('mentor')
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest('scheduled_at')
            ->take(5)
            ->get();

        return view('member.konsultasi', compact('mentors', 'myBookings', 'myHistory'));
    }

    // ── Ajukan booking baru ───────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mentor_id'    => ['required', 'uuid', 'exists:mentors,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'topic'        => ['nullable', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'in:30,60,90'],
        ], [
            'mentor_id.required'    => 'Pilih mentor terlebih dahulu.',
            'mentor_id.exists'      => 'Mentor tidak ditemukan.',
            'scheduled_at.required' => 'Pilih tanggal dan waktu sesi.',
            'scheduled_at.after'    => 'Jadwal harus di masa depan.',
        ]);

        $member = Auth::user()->member;
        $mentor = Mentor::findOrFail($validated['mentor_id']);

        // Cek: mentor harus sudah terverifikasi
        if (! $mentor->is_verified) {
            return back()->with('error', 'Mentor ini belum terverifikasi.');
        }

        // Cek: member tidak boleh punya booking pending/confirmed di waktu yang sama
        $conflict = $member->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Kamu sudah punya booking di waktu yang sama.');
        }

        // Cek: mentor tidak boleh punya booking confirmed di waktu yang sama
        $mentorConflict = $mentor->bookings()
            ->where('status', 'confirmed')
            ->where('scheduled_at', $validated['scheduled_at'])
            ->exists();

        if ($mentorConflict) {
            return back()->with('error', 'Mentor sudah ada sesi lain di waktu tersebut. Pilih waktu lain.');
        }

        Booking::create([
            'member_id'        => $member->id,
            'mentor_id'        => $mentor->id,
            'topic'            => $validated['topic'] ?? null,
            'scheduled_at'     => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'] ?? 60,
            'status'           => 'pending',
        ]);

        return redirect()
            ->route('member.konsultasi')
            ->with('success', 'Permintaan booking berhasil dikirim! Tunggu konfirmasi dari mentor.');
    }

    // ── Batalkan booking (hanya jika masih pending) ───────────────────────
    public function cancel(Booking $booking): RedirectResponse
    {
        $member = Auth::user()->member;

        // Pastikan ini booking milik member yang login
        abort_unless($booking->member_id === $member->id, 403);

        // Hanya booking pending yang bisa dibatalkan oleh member
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking yang masih pending yang bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()
            ->route('member.konsultasi')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
