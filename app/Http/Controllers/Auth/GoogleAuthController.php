<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // ─── Member Redirect ──────────────────────────────────────────────────────
    public function redirectMember()
    {
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
        $role = 'member';

        try {
            $rawState = request()->input('state');
            if ($rawState) {
                $decoded = json_decode(base64_decode($rawState), true);
                if (isset($decoded['role']) && in_array($decoded['role'], ['member', 'mentor'])) {
                    $role = $decoded['role'];
                }
            }
        } catch (\Throwable $e) {
            // pakai default 'member'
        }

        return $this->handleCallback($role);
    }

    // ─── Shared Processing Logic ──────────────────────────────────────────────
    private function handleCallback(string $role)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route($role . '.login')
                ->withErrors(['google' => 'Login Google gagal. Silakan coba lagi.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();
        $isNewMentor = false;

        if ($user) {
            if ($user->role !== $role) {
                return redirect()->route($role . '.login')
                    ->withErrors(['google' => "Email ini sudah terdaftar sebagai {$user->role}. Silakan login di halaman yang sesuai."]);
            }
        } else {
            if ($role === 'mentor') {
                $isNewMentor = true;
            }
        }

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

        if ($role === 'member' && !$user->member) {
            Member::create([
                'user_id'   => $user->id,
                'full_name' => $googleUser->getName(),
            ]);
        }

        if ($role === 'mentor' && !$user->mentor) {
            Mentor::create([
                'user_id'        => $user->id,
                'full_name'      => $googleUser->getName(),
                'certification'  => null,
                'specialization' => null,
                'bio'            => null,
            ]);
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        // Di Railway (cloud), session perlu di-save manual agar
        // tidak hilang saat pindah request (terutama di belakang proxy)
        Session::save();

        if ($role === 'mentor' && $isNewMentor) {
            return redirect()->route('mentor.complete-profile')
                ->with('info', 'Silakan lengkapi profil mentor Anda terlebih dahulu.');
        }

        return redirect()->route($role . '.dashboard');
    }
}
