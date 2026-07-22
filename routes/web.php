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

// Import Controller Booking untuk sisi Member
use App\Http\Controllers\Member\BookingController as MemberBookingController;
use App\Http\Controllers\Member\MealLogController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\SubscriptionPaymentController;

// ─── Public Landing Routes ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'showLoginSelection'])->name('login');
Route::get('/register', [HomeController::class, 'showRegisterSelection'])->name('register');
Route::get('/programs-preview', [App\Http\Controllers\Member\ProgramController::class, 'guestIndex'])->name('programs.preview');
Route::get('/log-nutrisi', [MealLogController::class, 'index'])->name('nutrition.preview');
Route::get('/konsultasi-preview', fn() => view('member.konsultasi'))->name('konsultasi.preview');
// Unified Google OAuth Callback (Handles both roles via session)
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleUnifiedCallback'])->name('auth.google.callback');
Route::post('/payments/midtrans/notification', [SubscriptionPaymentController::class, 'notification'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('midtrans.notification');


// ═══════════════════════════════════════════════════════════════════════════════
// MEMBER AUTH
// ═══════════════════════════════════════════════════════════════════════════════
Route::prefix('member')->name('member.')->group(function () {

    // 1. Hanya untuk Pengunjung yang BELUM LOGIN
    Route::middleware('guest')->group(function () {
        Route::get('/login',    [MemberAuthController::class, 'showLogin'])->name('login');
        Route::post('/login',   [MemberAuthController::class, 'login']);
        Route::get('/register', [MemberAuthController::class, 'showRegister'])->name('register');
        Route::post('/register',[MemberAuthController::class, 'register']);
    });

    // 2. Google OAuth Redirection — Member
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectMember'])->name('auth.google');

    // Halaman pembayaran tetap dapat diakses saat membership belum aktif.
    Route::middleware(['auth', 'role:member'])->group(function () {
        Route::get('/payment', [SubscriptionPaymentController::class, 'show'])->name('payment.show');
        Route::post('/payment/retry', [SubscriptionPaymentController::class, 'retry'])->name('payment.retry');
    });

    // 3. Hanya untuk Member yang SUDAH LOGIN & Memiliki Role Member
    Route::middleware(['auth', 'role:member', 'member.subscription'])->group(function () {

        // Dashboard Member
        Route::get('/dashboard', fn() => view('home'))->name('dashboard');

        // Log nutrisi memakai URL publik lama agar tautan navigasi tetap valid,
        // tetapi datanya hanya tersedia setelah membership aktif.
        Route::get('/log-nutrisi', [MealLogController::class, 'index'])->name('log-nutrisi');

        // Logout Member
        // ─── Fitur Manajemen Konsultasi & Booking Zoom Member ──────────────────
        Route::get('/konsultasi', [MemberBookingController::class, 'index'])->name('konsultasi');
        Route::post('/konsultasi', [MemberBookingController::class, 'store'])->name('konsultasi.store');
        Route::patch('/konsultasi/{booking}/cancel', [MemberBookingController::class, 'cancel'])->name('konsultasi.cancel');

        // Rute Program Latihan Akses Penuh untuk Member
        Route::get('/programs', [App\Http\Controllers\Member\ProgramController::class, 'index'])->name('programs.index');
        Route::post('/programs/{program}/enroll', [App\Http\Controllers\Member\ProgramController::class, 'enroll'])->name('programs.enroll');
        Route::patch('/programs/{program}/progress', [App\Http\Controllers\Member\ProgramController::class, 'updateProgress'])->name('programs.progress');

        // ─── Log Nutrisi (tambah & hapus entri) ─────────────────────────────
        Route::post('/log-nutrisi', [MealLogController::class, 'store'])->name('log-nutrisi.store');
        Route::delete('/log-nutrisi/{mealLog}', [MealLogController::class, 'destroy'])->name('log-nutrisi.destroy');

        // ─── Profil Saya (biodata + foto profil via Cloudinary) ─────────────
        Route::get('/profile', [MemberProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [MemberProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/photo', [MemberProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    });

    // Member yang belum membayar tetap harus bisa keluar dari akun.
    Route::middleware(['auth', 'role:member'])->post('/logout', [MemberAuthController::class, 'logout'])->name('logout');
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

        // Halaman "menunggu persetujuan admin" — mentor yang belum di-approve
        // (is_verified = false) dialihkan ke sini oleh EnsureMentorProfileComplete.
        // Sengaja hanya pakai middleware 'auth' (bukan grup yang di-gate di bawah)
        // supaya tidak terjadi redirect loop ke halaman ini sendiri.
        Route::get('/pending-verification', [MentorProfileController::class, 'pendingVerification'])->name('mentor.pending-verification');
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
            Route::delete('/profile/photo', [MentorProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
        });
    });
});

require __DIR__.'/admin.php';
