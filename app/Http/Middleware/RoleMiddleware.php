<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Pastikan user yang login memiliki role yang sesuai.
     * Sudah diperbaiki agar tidak memblokir halaman Guest seperti Login/Register.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Jika rute yang diakses adalah halaman khusus Guest (Login / Register), langsung loloskan!
        if ($request->routeIs('mentor.login') || $request->routeIs('mentor.register') ||
            $request->routeIs('member.login') || $request->routeIs('member.register')) {
            return $next($request);
        }

        // 2. Jika user belum login sama sekali dan mencoba masuk ke halaman steril/terproteksi
        if (! Auth::check()) {
            // Deteksi apakah dia sedang mencoba masuk ke area mentor atau member
            if ($request->is('mentor/*')) {
                return redirect()->route('mentor.login');
            }
            return redirect()->route('member.login');
        }

        $user = Auth::user();

        // 3. Jika user sudah login tapi rolenya tidak sesuai dengan rute yang diminta
        if (! in_array($user->role, $roles)) {
            return match ($user->role) {
                'member'      => redirect()->route('member.dashboard'),
                'mentor'      => redirect()->route('mentor.dashboard'),
                'super_admin' => redirect()->route('admin.dashboard'),
                default       => abort(403, 'Akses ditolak.'),
            };
        }

        return $next($request);
    }
}
