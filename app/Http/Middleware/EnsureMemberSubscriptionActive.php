<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $member = $request->user()?->member;

        if (! $member || ! $member->hasActiveSubscription()) {
            return redirect()->route('member.payment.show')
                ->with('info', 'Selesaikan pembayaran membership untuk mengakses seluruh fitur member.');
        }

        return $next($request);
    }
}
