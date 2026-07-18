<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL; // <-- INI YANG TADI HILANG!

// ── Auth Events ───────────────────────────────────────────────────────────────
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use App\Listeners\LogFailedLogin;
use App\Listeners\LogRegistered;

// ── Models ────────────────────────────────────────────────────────────────────
use App\Models\WorkoutProgram;
use App\Models\Booking;
use App\Models\Mentor;
use App\Models\Member;
use App\Models\MealLog;
use App\Models\ActivityLog;
use App\Models\MemberProgram;

// ── Observers ─────────────────────────────────────────────────────────────────
use App\Observers\WorkoutProgramObserver;
use App\Observers\BookingObserver;
use App\Observers\MentorObserver;
use App\Observers\MemberObserver;
use App\Observers\MealLogObserver;
use App\Observers\ActivityLogObserver;
use App\Observers\MemberProgramObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Auth event listeners ──────────────────────────────────────────────
        Event::listen(Login::class,      LogSuccessfulLogin::class);
        Event::listen(Logout::class,     LogSuccessfulLogout::class);
        Event::listen(Failed::class,     LogFailedLogin::class);
        Event::listen(Registered::class, LogRegistered::class);

        // ── Model observers ───────────────────────────────────────────────────
        WorkoutProgram::observe(WorkoutProgramObserver::class);
        Booking::observe(BookingObserver::class);
        Mentor::observe(MentorObserver::class);
        Member::observe(MemberObserver::class);
        MealLog::observe(MealLogObserver::class);
        // ActivityLog::observe(ActivityLogObserver::class);
        // MemberProgram::observe(MemberProgramObserver::class);

        // ── HTTPS Force Scheme ────────────────────────────────────────────────
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
