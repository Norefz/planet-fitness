<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ── Tampilkan halaman login admin ────────────────────────────
    public function showLogin()
    {
        // Kalau sudah login sebagai admin → langsung ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // ── Proses login admin ───────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Coba login menggunakan guard 'admin'
        // Guard admin dikonfigurasi di config/auth.php dengan provider 'admins'
        // yang menggunakan User model dengan scope role = 'super_admin'
        $remember = $request->boolean('remember');

        if (! Auth::guard('admin')->attempt($credentials, $remember)) {
            // Catat percobaan login gagal
            AuditLog::create([
                'action'       => 'admin_login_failed',
                'details'      => "Percobaan login gagal untuk email: {$credentials['email']}",
                'ip_address'   => $request->ip(),
                'performed_at' => now(),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        $user = Auth::guard('admin')->user();

        // Pastikan user punya role super_admin
        if ($user->role !== 'super_admin') {
            Auth::guard('admin')->logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun ini tidak memiliki akses admin.']);
        }

        // Pastikan akun aktif
        if (! $user->is_active) {
            Auth::guard('admin')->logout();
            return back()
                ->withErrors(['email' => 'Akun admin ini dinonaktifkan. Hubungi super admin.']);
        }

        $request->session()->regenerate();

        // Catat login berhasil ke audit log
        AuditLog::create([
            'admin_id'     => $user->superAdmin?->id,
            'action'       => 'admin_login',
            'details'      => "<strong>{$user->superAdmin?->full_name}</strong> masuk ke sistem admin.",
            'ip_address'   => $request->ip(),
            'performed_at' => now(),
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }

    // ── Logout admin ─────────────────────────────────────────────
    public function logout(Request $request)
    {
        $name = Auth::guard('admin')->user()?->superAdmin?->full_name ?? 'Admin';

        AuditLog::create([
            'admin_id'     => Auth::guard('admin')->user()?->superAdmin?->id,
            'action'       => 'admin_logout',
            'details'      => "<strong>{$name}</strong> keluar dari sistem admin.",
            'ip_address'   => $request->ip(),
            'performed_at' => now(),
        ]);

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Kamu berhasil keluar dari admin panel.');
    }
}
