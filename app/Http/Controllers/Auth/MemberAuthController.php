<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberAuthController extends Controller
{
    // ─── Login ───────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('member.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Cek role — jangan kasih tau user bahwa role salah (security)
        if (! $user || $user->role !== 'member') {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }

        // Cek apakah Google user mencoba login dengan password
        if (is_null($user->password)) {
            return back()->withErrors(['email' => 'Akun ini terdaftar via Google. Silakan gunakan tombol "Masuk dengan Google".'])->onlyInput('email');
        }

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => 'member'], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }

        if (! Auth::user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun kamu dinonaktifkan. Hubungi admin.']);
        }

        $request->session()->regenerate();
        return Auth::user()->member?->hasActiveSubscription()
            ? redirect()->route('member.dashboard')
            : redirect()->route('member.payment.show');
    }

    // ─── Register ────────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('member.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'member',
            'is_active' => true,
        ]);

        Member::create([
            'user_id'   => $user->id,
            'full_name' => $validated['name'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.payment.show');
    }

    // ─── Logout ──────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('member.login');
    }
}
