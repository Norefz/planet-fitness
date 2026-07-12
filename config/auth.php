<?php

// ══════════════════════════════════════════════════════════════════════
//  config/auth.php
//  Tambahkan guard 'admin' dan provider 'admins' di file ini.
//
//  Guard 'admin' menggunakan tabel 'users' dengan scope role='super_admin'
//  Ini cara paling clean tanpa tabel auth terpisah.
// ══════════════════════════════════════════════════════════════════════

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [

        // ── Guard default (member & mentor) ──────────────────────
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // ── Guard admin (super_admin) ─────────────────────────────
        // Menggunakan session terpisah agar admin dan member
        // bisa login bersamaan tanpa konflik session.
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',   // lihat providers di bawah
        ],
    ],

    'providers' => [

        // ── Provider default: semua user (member & mentor) ────────
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // ── Provider admin: hanya user dengan role super_admin ────
        // Kita pakai UserAdminProvider custom agar bisa scope by role.
        // ATAU: pakai model User biasa dan filter di middleware.
        // Pilihan 2 lebih simple → kita pakai itu.
        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
