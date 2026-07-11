<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Registered;

class LogRegistered
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (! in_array($user->role ?? '', ['member', 'mentor'])) {
            return;
        }

        $label = $user->role === 'mentor' ? 'Mentor' : 'Member';

        AuditLog::record(
            action:      "{$user->role}_registered",
            details:     "{$label} baru terdaftar: {$user->name} ({$user->email}).",
            targetTable: 'users',
            targetId:    $user->id,
        );
    }
}
