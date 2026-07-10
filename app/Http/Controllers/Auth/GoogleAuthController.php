<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // ─── Member Redirect ──────────────────────────────────────────────────────
    public function redirectMember()
    {
        session(['oauth_role' => 'member']);

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // ─── Mentor Redirect ──────────────────────────────────────────────────────
    public function redirectMentor()
    {
        session(['oauth_role' => 'mentor']);

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // ─── Unified Callback (dipanggil dari route /auth/google/callback) ────────
    public function handleUnifiedCallback()
    {
        $role = session('oauth_role', 'member');

        return $this->handleCallback($role);
    }

    // ─── Shared Processing Logic ──────────────────────────────────────────────
    private function handleCallback(string $role)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route($role . '.login')
                ->withErrors(['google' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // Cek apakah email sudah terdaftar dengan role berbeda
        $existing = User::where('email', $googleUser->getEmail())->first();

        if ($existing && $existing->role !== $role) {
            return redirect()->route($role . '.login')
                ->withErrors(['google' => 'Email ini sudah terdaftar sebagai ' . $existing->role . '. Silakan gunakan halaman yang sesuai.']);
        }

        // Cek apakah ini mentor baru yang perlu onboarding
        $isNewMentor = false;
        if ($role === 'mentor') {
            if (!$existing || !$existing->mentor) {
                $isNewMentor = true;
            }
        }

        // Upsert user
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'      => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'role'      => $role,
                'is_active' => true,
                'password'  => null,
            ]
        );

        // Buat profil member jika belum ada
        if ($role === 'member' && !$user->member) {
            Member::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
            ]);
        }

        // Buat profil mentor jika belum ada (kosong dulu, diisi lewat onboarding)
        if ($role === 'mentor' && !$user->mentor) {
            Mentor::create([
                'user_id'        => $user->id,
                'full_name'      => $googleUser->getName(),
                'certification'  => null,
                'specialization' => null,
                'bio'            => null,
            ]);
        }

        Auth::login($user);
        session()->forget('oauth_role');
        session()->regenerate();

        // Mentor baru → arahkan ke halaman lengkapi profil dulu
        if ($role === 'mentor' && $isNewMentor) {
            return redirect()->route('mentor.complete-profile')
                ->with('info', 'Silakan lengkapi profil mentor Anda terlebih dahulu.');
        }

        return redirect()->route($role . '.dashboard');
    }
}
