<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // ─── METHOD BARU: Menampilkan Form Onboarding Google ─────────────────────
    public function showOnboarding(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $mentor = $user->mentorProfile();

        // Jika mentor ternyata sudah melengkapi datanya, langsung alihkan —
        // ke dashboard jika sudah diverifikasi admin, atau ke halaman menunggu jika belum.
        if (!empty($mentor->bio) && !empty($mentor->specialization)) {
            return redirect()->route($mentor->is_verified ? 'mentor.dashboard' : 'mentor.pending-verification');
        }

        // Tampilkan halaman form onboarding yang kita buat di nomor 4
        return view('mentor.complete-profile', compact('user', 'mentor'));
    }

    // ─── METHOD BARU: Memproses Submit Form Onboarding Google ─────────────────
    public function submitOnboarding(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $mentor = $user->mentorProfile();

        // Validasi input form wajib onboarding
        $validated = $request->validate([
            'certification'  => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'bio'            => ['required', 'string', 'max:1000'],
        ]);

        // Update data spesifik milik profil mentor
        $mentor->update([
            'bio'            => $validated['bio'],
            'certification'  => $validated['certification'],
            'specialization' => $validated['specialization'],
        ]);

        // Setelah sukses: mentor yang belum diverifikasi admin diarahkan ke
        // halaman menunggu persetujuan, bukan langsung ke dashboard.
        if (! $mentor->is_verified) {
            return redirect()
                ->route('mentor.pending-verification')
                ->with('success', 'Profil mentor Anda berhasil dilengkapi dan sedang menunggu persetujuan admin.');
        }

        return redirect()
            ->route('mentor.dashboard')
            ->with('success', 'Profil mentor Anda berhasil dilengkapi. Selamat datang!');
    }

    // ─── METHOD BARU: Halaman "menunggu persetujuan admin" ───────────────────
    public function pendingVerification(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $mentor = $user->mentorProfile();

        // Sebelumnya halaman ini selalu menampilkan layar "menunggu" tanpa
        // pernah mengecek ulang status — jadi walau admin sudah approve,
        // mentor harus logout/login dulu baru bisa masuk dashboard.
        // Sekarang setiap refresh/kunjungan ke sini akan cek ulang: begitu
        // is_verified sudah true, langsung diarahkan ke dashboard.
        if ($mentor->is_verified) {
            return redirect()->route('mentor.dashboard');
        }

        return view('mentor.pending-verification', compact('mentor'));
    }

    // ─── Fitur Edit Profil Biasa (Bawaanmu) ───────────────────────────────────
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $mentor = $user->mentorProfile();

        return view('mentor.profile.edit', compact('user', 'mentor'));
    }

    // ─── Fitur Update Profil Biasa (Bawaanmu) ─────────────────────────────────
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $mentor = $user->mentorProfile();

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'bio'             => ['nullable', 'string', 'max:1000'],
            'certification'   => ['nullable', 'string', 'max:255'],
            'specialization'  => ['nullable', 'string', 'max:255'],
        ]);

        $user->update(['name' => $validated['name']]);

        $mentor->update([
            'full_name'      => $validated['name'],
            'bio'            => $validated['bio'] ?? null,
            'certification'  => $validated['certification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
        ]);

        return redirect()
            ->route('mentor.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
