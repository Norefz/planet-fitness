<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust semua proxy — diperlukan di Railway, Heroku, dan cloud platform lain
     * yang berada di belakang load balancer.
     *
     * Tanpa ini, Laravel tidak tahu request datang via HTTPS,
     * sehingga session cookie yang di-set dengan secure=true tidak terbaca
     * → Auth::check() selalu false setelah redirect → login loop.
     */
    protected $proxies = '*';

    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
