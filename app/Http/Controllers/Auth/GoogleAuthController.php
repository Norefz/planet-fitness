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

        // FIXED: Ditambahkan prompt select_account agar bisa ganti akun Google
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // ─── Mentor Redirect ──────────────────────────────────────────────────────
    public function redirectMentor()
    {
        session(['oauth_role' => 'mentor']);

        // FIXED: Ditambahkan prompt select_account agar bisa ganti akun Google
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // ─── NEW UNIFIED CALLBACK METHOD ─────────────────────────────────────────
    public function handleUnifiedCallback()
    {
        // Check which role was stored in the session when they clicked the button
        $role = session('oauth_role', 'member');

        return $this->handleCallback($role);
    }

    // ─── Shared Processing Logic ─────────────────────────────────────────────
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

        // Buat profile jika belum ada
        if ($role === 'member' && ! $user->member) {
            Member::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
            ]);
        }

        if ($role === 'mentor' && ! $user->mentor) {
            Mentor::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
            ]);
        }

        Auth::login($user);
        session()->forget('oauth_role');
        session()->regenerate();

        // Safe string concatenation to fix the previous typo
        return redirect()->route($role . '.dashboard');
    }
     // ─── Shared Processing Logic ─────────────────────────────────────────────
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

        // Jalur pengecekan apakah ini pendaftaran mentor baru via Google
        $isNewMentor = false;
        if ($role === 'mentor') {
            // Jika user belum terdaftar sama sekali di database
            if (!$existing) {
                $isNewMentor = true;
            } else {
                // Jika user-nya ada tapi profil mentor-nya belum dibentuk
                if (!$existing->mentor) {
                    $isNewMentor = true;
                }
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

        // Buat profile jika belum ada
        if ($role === 'member' && ! $user->member) {
            Member::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
            ]);
        }

        if ($role === 'mentor' && ! $user->mentor) {
            Mentor::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
                'certification'  => null, // Dikosongkan dulu agar nanti diisi lewat onboarding
                'specialization' => null,
                'bio'            => null,
            ]);
        }

        Auth::login($user);
        session()->forget('oauth_role');
        session()->regenerate();

        // JALUR REDIRECT: Jika dia mentor baru yang belum melengkapi profil via Google
        if ($role === 'mentor' && $isNewMentor) {
            return redirect()->route('mentor.complete-profile')
                ->with('info', 'Silakan lengkapi profil mentor Anda terlebih dahulu.');
        }

        return redirect()->route($role . '.dashboard');
    }
}
