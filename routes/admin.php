<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════════════
//  ADMIN ROUTES — prefix: /admin, semua terisolasi
//
//  Cara daftarkan file ini:
//  Di bootstrap/app.php tambahkan:
//      web: [__DIR__.'/../routes/web.php', __DIR__.'/../routes/admin.php'],
//  ATAU di routes/web.php tambahkan:
//      require __DIR__.'/admin.php';
// ══════════════════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->group(function () {

    // ── Public: login (tidak butuh auth) ──────────────────────
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // ── Protected: semua halaman admin ────────────────────────
    Route::middleware(\App\Http\Middleware\EnsureIsAdmin::class)->group(function () {

        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // ── Pencarian global ─────────────────────────────────────
        Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'index'])->name('search');

        // ── Manajemen Member ──────────────────────────────────
        Route::prefix('members')->name('members.')->group(function () {
            Route::get('/',               [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('index');
            Route::get('/{member}',       [\App\Http\Controllers\Admin\MemberController::class, 'show'])->name('show');
            Route::patch('/{member}/toggle-active',
                                          [\App\Http\Controllers\Admin\MemberController::class, 'toggleActive'])->name('toggle-active');
        });
        // alias agar route('admin.members') bisa dipanggil
        Route::get('/members', [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('members');

        // ── Manajemen Mentor ──────────────────────────────────
        Route::prefix('mentors')->name('mentors.')->group(function () {
            Route::get('/',                    [\App\Http\Controllers\Admin\MentorController::class, 'index'])->name('index');
            Route::get('/{mentor}',            [\App\Http\Controllers\Admin\MentorController::class, 'show'])->name('show');
            Route::patch('/{mentor}/verify',   [\App\Http\Controllers\Admin\MentorController::class, 'verify'])->name('verify');
            Route::patch('/{mentor}/toggle-active',
                                               [\App\Http\Controllers\Admin\MentorController::class, 'toggleActive'])->name('toggle-active');
        });
        Route::get('/mentors', [\App\Http\Controllers\Admin\MentorController::class, 'index'])->name('mentors');

        // ── Program Latihan ───────────────────────────────────
        Route::prefix('programs')->name('programs.')->group(function () {
            Route::get('/',                    [\App\Http\Controllers\Admin\ProgramController::class, 'index'])->name('index');
            Route::get('/{program}',           [\App\Http\Controllers\Admin\ProgramController::class, 'show'])->name('show');
            Route::patch('/{program}/toggle',  [\App\Http\Controllers\Admin\ProgramController::class, 'togglePublish'])->name('toggle');
            Route::delete('/{program}',        [\App\Http\Controllers\Admin\ProgramController::class, 'destroy'])->name('destroy');
        });
        Route::get('/programs', [\App\Http\Controllers\Admin\ProgramController::class, 'index'])->name('programs');

        // ── Booking Konsultasi ────────────────────────────────
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/',                    [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('index');
            Route::patch('/{booking}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('confirm');
            Route::patch('/{booking}/cancel',  [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('cancel');
        });
        Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings');

        // ── Laporan & Analitik ────────────────────────────────
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');

        // ── Log Aktivitas Member & Mentor (semua admin) ───────
        Route::get('/logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs');

        // ── Log tindakan admin (hanya Super Admin utama) ──────
        Route::get('/logs/admin', [\App\Http\Controllers\Admin\LogController::class, 'adminIndex'])
            ->middleware('admin.head')
            ->name('admin-logs');

        // ── Konfigurasi Sistem ────────────────────────────────
        Route::get('/config',             [\App\Http\Controllers\Admin\ConfigController::class, 'index'])->name('config');
        Route::post('/config',            [\App\Http\Controllers\Admin\ConfigController::class, 'update'])->name('config.update');
        Route::post('/config/admins',     [\App\Http\Controllers\Admin\ConfigController::class, 'storeAdmin'])->name('config.admins.store');
        Route::post('/config/clear-logs', [\App\Http\Controllers\Admin\ConfigController::class, 'clearLogs'])
            ->middleware('admin.head')
            ->name('config.clear-logs');
        Route::post('/config/reset',      [\App\Http\Controllers\Admin\ConfigController::class, 'resetDefaults'])->name('config.reset');

    });
});
