<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MentorAuthController extends Controller
{
    // ─── Login ───────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('mentor.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || $user->role !== 'mentor') {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }

        if (is_null($user->password)) {
            return back()->withErrors(['email' => 'Akun ini terdaftar via Google. Silakan gunakan tombol "Masuk dengan Google".'])->onlyInput('email');
        }

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => 'mentor'], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }

        if (! Auth::user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun mentor kamu sedang dalam proses verifikasi atau dinonaktifkan.']);
        }

        $request->session()->regenerate();
        return redirect()->route('mentor.dashboard');
    }

    // ─── Register ────────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('mentor.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'bio'             => ['nullable', 'string', 'max:1000'],
            'certification'   => ['nullable', 'string', 'max:255'],
            'specialization'  => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'mentor',
            'is_active' => false, // Mentor perlu verifikasi admin dulu
        ]);

        Mentor::create([
            'user_id'        => $user->id,
            'full_name'      => $validated['name'],
            'bio'            => $validated['bio'] ?? null,
            'certification'  => $validated['certification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'is_verified'    => false,
        ]);

        // Jangan auto-login — tunggu verifikasi admin
        return redirect()->route('mentor.login')
            ->with('success', 'Pendaftaran berhasil! Akun kamu sedang dalam proses verifikasi. Kami akan menghubungi kamu via email.');
    }

    // ─── Logout ──────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('mentor.login');
    }
}
