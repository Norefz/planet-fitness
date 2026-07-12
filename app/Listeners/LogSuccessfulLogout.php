<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if (! $user || ! in_array($user->role ?? '', ['member', 'mentor'])) {
            return;
        }

        $label = $user->role === 'mentor' ? 'Mentor' : 'Member';

        AuditLog::record(
            action:      "{$user->role}_logout",
            details:     "{$label} {$user->name} ({$user->email}) keluar dari sistem.",
            targetTable: 'users',
            targetId:    $user->id,
        );
    }
}
