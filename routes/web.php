<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\Auth\MentorAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Mentor\WorkoutProgramController;
use App\Http\Controllers\Mentor\BookingController as MentorBookingController;
use App\Http\Controllers\Mentor\ProfileController as MentorProfileController;

// ─── Public Landing Routes ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'showLoginSelection'])->name('login');
Route::get('/register', [HomeController::class, 'showRegisterSelection'])->name('register');
Route::get('/programs-preview', [App\Http\Controllers\Member\ProgramController::class, 'guestIndex'])->name('programs.preview');
// Unified Google OAuth Callback (Handles both roles via session)
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleUnifiedCallback'])->name('auth.google.callback');


// ═══════════════════════════════════════════════════════════════════════════════
// MEMBER AUTH
// ═══════════════════════════════════════════════════════════════════════════════
Route::prefix('member')->name('member.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/login',    [MemberAuthController::class, 'showLogin'])->name('login');
        Route::post('/login',   [MemberAuthController::class, 'login']);
        Route::get('/register', [MemberAuthController::class, 'showRegister'])->name('register');
        Route::post('/register',[MemberAuthController::class, 'register']);
    });

Route::prefix('member')->name('member.')->group(function () {
    Route::middleware(['auth', 'role:member'])->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

        // Tambahkan rute program member di sini:
        Route::get('/programs', [App\Http\Controllers\Member\ProgramController::class, 'index'])->name('programs.index');
    });
});

    // Google OAuth Redirection — Member
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectMember'])->name('auth.google');

    // Authenticated member
    Route::middleware(['auth', 'role:member'])->group(function () {
        // Pointing to the unified home blade view
        Route::get('/dashboard', fn() => view('home'))->name('dashboard');
        Route::post('/logout',   [MemberAuthController::class, 'logout'])->name('logout');
    });
});


// ═══════════════════════════════════════════════════════════════════════════════
// MENTOR AUTH
// ═══════════════════════════════════════════════════════════════════════════════
Route::prefix('mentor')->group(function () {

    // 1. RUTE ONBOARDING: Dibuat mandiri dengan nama mutlak 'mentor.complete-profile'
    // Memakai middleware 'auth' saja (tanpa role:mentor atau ensure profile complete) agar tidak dicegat di tengah jalan
    Route::middleware(['auth'])->group(function () {
        Route::get('/complete-profile', [MentorProfileController::class, 'showOnboarding'])->name('mentor.complete-profile');
        Route::post('/complete-profile', [MentorProfileController::class, 'submitOnboarding'])->name('mentor.complete-profile.submit');
    });

    // 2. KELOMPOK RUTE LAINNYA YANG OTOMATIS BER-NAME 'mentor.'
    Route::name('mentor.')->group(function () {

        // Guest only (Halaman Login & Register biasa)
        Route::middleware('guest')->group(function () {
            Route::get('/login',    [MentorAuthController::class, 'showLogin'])->name('login');
            Route::post('/login',   [MentorAuthController::class, 'login']);
            Route::get('/register', [MentorAuthController::class, 'showRegister'])->name('register');
            Route::post('/register',[MentorAuthController::class, 'register']);
        });

        // Google OAuth Redirection — Mentor
        Route::get('/auth/google', [GoogleAuthController::class, 'redirectMentor'])->name('auth.google');

        // Authenticated Mentor (Dashboard, Programs, Bookings, dan Profile biasa)
        // Diproteksi ketat oleh Juri Onboarding (EnsureMentorProfileComplete)
        Route::middleware(['auth', 'role:mentor', \App\Http\Middleware\EnsureMentorProfileComplete::class])->group(function () {
            Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
            Route::post('/logout',   [MentorAuthController::class, 'logout'])->name('logout');

            // CRUD Program Latihan
            Route::resource('programs', WorkoutProgramController::class)
                ->except(['show'])
                ->parameters(['programs' => 'program']);
            Route::patch('/programs/{program}/toggle-status', [WorkoutProgramController::class, 'toggleStatus'])
                ->name('programs.toggle-status');

            // Manajemen Konsultasi (Booking)
            Route::get('/bookings', [MentorBookingController::class, 'index'])->name('bookings.index');
            Route::patch('/bookings/{booking}', [MentorBookingController::class, 'update'])->name('bookings.update');

            // Profil Pengaturan Dashboard Biasa
            Route::get('/profile', [MentorProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [MentorProfileController::class, 'update'])->name('profile.update');
        });
    });
});
