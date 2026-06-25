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
    // ─── Member ──────────────────────────────────────────────────────────────

    public function redirectMember()
    {
        // Simpan role di session agar callback tahu ini member
        session(['oauth_role' => 'member']);
        return Socialite::driver('google')->redirect();
    }

    public function callbackMember()
    {
        return $this->handleCallback('member');
    }

    // ─── Mentor ──────────────────────────────────────────────────────────────

    public function redirectMentor()
    {
        session(['oauth_role' => 'mentor']);
        return Socialite::driver('google')->redirect();
    }

    public function callbackMentor()
    {
        return $this->handleCallback('mentor');
    }

    // ─── Shared logic ────────────────────────────────────────────────────────

    private function handleCallback(string $role)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route("{$role}.login")
                ->withErrors(['google' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // Cek apakah email sudah terdaftar dengan role berbeda
        $existing = User::where('email', $googleUser->getEmail())->first();

        if ($existing && $existing->role !== $role) {
            return redirect()->route("{$role}.login")
                ->withErrors(['google' => 'Email ini sudah terdaftar sebagai ' . $existing->role . '. Silakan gunakan halaman yang sesuai.']);
        }

        // Upsert user
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'          => $googleUser->getName(),
                'google_id'     => $googleUser->getId(),
                'avatar'        => $googleUser->getAvatar(),
                'role'          => $role,
                'is_active'     => true,
                'password'      => null, // Google user tidak punya password
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
            // Mentor Google login → arahkan ke complete-profile setelah login
            Mentor::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
            ]);
        }

        Auth::login($user);
        session()->forget('oauth_role');
        session()->regenerate();

        return redirect()->route("{$role}.dashboard");
    }
}
