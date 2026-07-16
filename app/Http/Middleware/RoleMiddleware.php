<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Lewatkan halaman guest (login/register) tanpa cek apapun
        if ($request->routeIs('mentor.login', 'mentor.register', 'member.login', 'member.register')) {
            return $next($request);
        }

        // 2. Belum login → redirect ke halaman login yang sesuai
        //    TANPA intended() agar tidak masuk redirect loop
        if (! Auth::check()) {
            if ($request->is('mentor/*')) {
                return redirect()->route('mentor.login');
            }
            // Default: arahkan ke login member
            // Tidak pakai intended() karena bisa menyebabkan loop di cloud
            return redirect()->route('member.login');
        }

        $user = Auth::user();

        // 3. Sudah login tapi role tidak cocok dengan route yang diminta
        if (! in_array($user->role, $roles)) {
            // Redirect ke dashboard role yang sebenarnya
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
