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
     * Contoh penggunaan di route: middleware('role:member')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('member.login');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles)) {
            // Arahkan ke dashboard yang sesuai dengan role-nya
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
