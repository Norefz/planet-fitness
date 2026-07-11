<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsAdmin
{
    /**
     * Cek apakah request datang dari admin yang sudah login.
     * Kalau belum → redirect ke halaman login admin.
     * Kalau sudah login tapi bukan super_admin → abort 403.
     */
    public function handle(Request $request, Closure $next)
    {
        // Belum login via guard 'admin'
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan masuk terlebih dahulu untuk mengakses admin panel.');
        }

        $user = Auth::guard('admin')->user();

        // Sudah login tapi role bukan super_admin
        if ($user->role !== 'super_admin') {
            Auth::guard('admin')->logout();
            abort(403, 'Akses ditolak. Halaman ini hanya untuk administrator.');
        }

        // Akun dinonaktifkan
        if (! $user->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Akun admin Anda dinonaktifkan.');
        }

        return $next($request);
    }
}
