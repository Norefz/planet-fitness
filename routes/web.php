<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\Auth\MentorAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Mentor\WorkoutProgramController;
use App\Http\Controllers\Mentor\WorkoutExerciseController;
use App\Http\Controllers\Mentor\BookingController as MentorBookingController;
use App\Http\Controllers\Mentor\ProfileController as MentorProfileController;
use App\Http\Controllers\Mentor\StatisticsController as MentorStatisticsController;

// ─── Public Landing Routes ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'showLoginSelection'])->name('login');
Route::get('/register', [HomeController::class, 'showRegisterSelection'])->name('register');

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
Route::prefix('mentor')->name('mentor.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/login',    [MentorAuthController::class, 'showLogin'])->name('login');
        Route::post('/login',   [MentorAuthController::class, 'login']);
        Route::get('/register', [MentorAuthController::class, 'showRegister'])->name('register');
        Route::post('/register',[MentorAuthController::class, 'register']);
    });

    // Google OAuth Redirection — Mentor
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectMentor'])->name('auth.google');

    // Authenticated mentor
    Route::middleware(['auth', 'role:mentor'])->group(function () {
        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',   [MentorAuthController::class, 'logout'])->name('logout');

        // ─── CRUD Program Latihan ───────────────────────────────────────────
        Route::resource('programs', WorkoutProgramController::class)
            ->parameters(['programs' => 'program']);
        Route::patch('/programs/{program}/toggle-status', [WorkoutProgramController::class, 'toggleStatus'])
            ->name('programs.toggle-status');

        // ─── Latihan di dalam Program (banyak jenis latihan per program) ────
        Route::post('/programs/{program}/exercises', [WorkoutExerciseController::class, 'store'])->name('programs.exercises.store');
        Route::put('/programs/{program}/exercises/{exercise}', [WorkoutExerciseController::class, 'update'])->name('programs.exercises.update');
        Route::delete('/programs/{program}/exercises/{exercise}', [WorkoutExerciseController::class, 'destroy'])->name('programs.exercises.destroy');
        Route::patch('/programs/{program}/exercises/{exercise}/move', [WorkoutExerciseController::class, 'move'])->name('programs.exercises.move');

        // ─── Statistik & Progres Member ──────────────────────────────────────
        Route::get('/statistics', [MentorStatisticsController::class, 'index'])->name('statistics.index');

        // ─── Manajemen Konsultasi (Booking) ─────────────────────────────────
        Route::get('/bookings', [MentorBookingController::class, 'index'])->name('bookings.index');
        Route::patch('/bookings/{booking}', [MentorBookingController::class, 'update'])->name('bookings.update');

        // ─── Profil & Sertifikasi ────────────────────────────────────────────
        Route::get('/profile', [MentorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [MentorProfileController::class, 'update'])->name('profile.update');
    });
});