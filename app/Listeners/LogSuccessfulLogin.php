<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Skip admin — sudah dicatat manual di AdminAuthController
        if (! in_array($user->role ?? '', ['member', 'mentor'])) {
            return;
        }

        $label = $user->role === 'mentor' ? 'Mentor' : 'Member';

        AuditLog::record(
            action:      "{$user->role}_login",
            details:     "{$label} {$user->name} ({$user->email}) masuk ke sistem.",
            targetTable: 'users',
            targetId:    $user->id,
        );
    }
}
