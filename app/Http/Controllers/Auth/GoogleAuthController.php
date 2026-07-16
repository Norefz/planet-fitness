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
        // Kirim role lewat 'state' parameter Google OAuth
        // State dikembalikan Google saat callback — tidak bergantung session sama sekali
        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
                'state'  => base64_encode(json_encode(['role' => 'member'])),
            ])
            ->redirect();
    }

    // ─── Mentor Redirect ──────────────────────────────────────────────────────
    public function redirectMentor()
    {
        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
                'state'  => base64_encode(json_encode(['role' => 'mentor'])),
            ])
            ->redirect();
    }

    // ─── Unified Callback ─────────────────────────────────────────────────────
    public function handleUnifiedCallback()
    {
        // Baca role dari state yang dikembalikan Google
        // Tidak bergantung session — aman di Railway/cloud
        $role = 'member'; // default fallback

        try {
            $rawState = request()->input('state');
            if ($rawState) {
                $decoded = json_decode(base64_decode($rawState), true);
                if (isset($decoded['role']) && in_array($decoded['role'], ['member', 'mentor'])) {
                    $role = $decoded['role'];
                }
            }
        } catch (\Throwable $e) {
            // Pakai default 'member'
        }

        return $this->handleCallback($role);
    }

    // ─── Shared Processing Logic ──────────────────────────────────────────────
    private function handleCallback(string $role)
    {
        try {
            // stateless() aman karena kita tidak bergantung session untuk validasi state
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route($role . '.login')
                ->withErrors(['google' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // Cek apakah user sudah terdaftar
        $user = User::where('email', $googleUser->getEmail())->first();

        $isNewMentor = false;

        if ($user) {
            // User ada tapi role berbeda → tolak
            if ($user->role !== $role) {
                return redirect()->route($role . '.login')
                    ->withErrors(['google' => "Email ini sudah terdaftar sebagai {$user->role}. Silakan login di halaman yang sesuai."]);
            }
        } else {
            if ($role === 'mentor') {
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

        // Buat profil mentor jika belum ada (onboarding nanti)
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
        session()->regenerate();

        // Mentor baru → arahkan ke onboarding
        if ($role === 'mentor' && $isNewMentor) {
            return redirect()->route('mentor.complete-profile')
                ->with('info', 'Silakan lengkapi profil mentor Anda terlebih dahulu.');
        }

        return redirect()->route($role . '.dashboard');
    }
}
