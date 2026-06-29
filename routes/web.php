<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\Auth\MentorAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\HomeController;

// ─── Redirect root ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

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

    // Google OAuth — Member
    Route::get('/auth/google',          [GoogleAuthController::class, 'redirectMember'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callbackMember'])->name('auth.google.callback');

    // Authenticated member
    Route::middleware(['auth', 'role:member'])->group(function () {
        Route::get('/dashboard', fn() => view('member.dashboard'))->name('dashboard');
        Route::post('/logout',   [MemberAuthController::class, 'logout'])->name('logout');
    });
});

// ═══════════════════════════════════════════════════════════════════════════════
// MENTOR AUTH
// ═══════════════════════════════════════════════════════════════════════════════
Route::prefix('mentor')->name('mentor.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/login',    [MentorAuthController::class, 'showLogin'])->name('login');
        Route::post('/login',   [MentorAuthController::class, 'login']);
        Route::get('/register', [MentorAuthController::class, 'showRegister'])->name('register');
        Route::post('/register',[MentorAuthController::class, 'register']);
    });

    // Google OAuth — Mentor
    Route::get('/auth/google',          [GoogleAuthController::class, 'redirectMentor'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callbackMentor'])->name('auth.google.callback');

    // Authenticated mentor
    Route::middleware(['auth', 'role:mentor'])->group(function () {
        Route::get('/dashboard', fn() => view('mentor.dashboard'))->name('dashboard');
        Route::post('/logout',   [MentorAuthController::class, 'logout'])->name('logout');
    });
});
