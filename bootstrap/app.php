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
        $middleware->alias([
            // Sudah ada sebelumnya
            'role'                    => \App\Http\Middleware\RoleMiddleware::class,
            'mentor.profile.complete' => \App\Http\Middleware\EnsureMentorProfileComplete::class,

            // Tambahan baru untuk admin guard
            'admin'                   => \App\Http\Middleware\EnsureIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
