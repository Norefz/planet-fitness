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

        // Jika mentor ternyata sudah melengkapi datanya, langsung alihkan ke dashboard
        if (!empty($mentor->bio) && !empty($mentor->specialization)) {
            return redirect()->route('mentor.dashboard');
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

        // Setelah sukses, lempar langsung ke Dashboard utama Mentor!
        return redirect()
            ->route('mentor.dashboard')
            ->with('success', 'Profil mentor Anda berhasil dilengkapi. Selamat datang!');
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
