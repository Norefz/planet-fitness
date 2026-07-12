<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'unknown';
        $role  = $event->user?->role ?? 'unknown';

        // Hanya catat untuk guard web (member & mentor)
        // guard 'admin' sudah punya handling sendiri
        if ($event->guard === 'admin') {
            return;
        }

        AuditLog::record(
            action:      'login_failed',
            details:     "Login gagal untuk email: {$email} (role: {$role}).",
            targetTable: 'users',
            targetId:    $event->user?->id,
        );
    }
}
