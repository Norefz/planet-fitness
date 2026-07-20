<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',   // ← tambahan: route admin terisolasi
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Laravel sudah otomatis mendaftarkan Illuminate\Http\Middleware\TrustProxies
        // bawaan di stack global (tanpa proxy yang dipercaya sama sekali secara default).
        // Menambahkan class kustom kita lewat prepend() saja tidak cukup — TrustProxies
        // bawaan tadi tetap jalan setelahnya dan me-reset ulang status trusted proxy-nya.
        // Jadi kita GANTI slot bawaan itu dengan versi kustom kita (bukan menambah baru),
        // supaya cuma ada satu TrustProxies yang aktif & terkonfigurasi dengan benar.
        $middleware->replace(
            \Illuminate\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrustProxies::class,
        );

        $middleware->alias([
            // Sudah ada sebelumnya
            'role'                    => \App\Http\Middleware\RoleMiddleware::class,
            'mentor.profile.complete' => \App\Http\Middleware\EnsureMentorProfileComplete::class,

            // Tambahan baru untuk admin guard
            'admin'                   => \App\Http\Middleware\EnsureIsAdmin::class,
            'admin.head'              => \App\Http\Middleware\EnsureIsHeadAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
