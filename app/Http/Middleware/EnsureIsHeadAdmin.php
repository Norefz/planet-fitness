<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsHeadAdmin
{
    /**
     * Restrict sensitive audit-log operations to the primary Super Admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isHeadAdmin = (bool) Auth::guard('admin')->user()?->superAdmin?->is_head;

        abort_unless($isHeadAdmin, 403, 'Hanya Super Admin utama yang dapat mengakses log admin.');

        return $next($request);
    }
}
