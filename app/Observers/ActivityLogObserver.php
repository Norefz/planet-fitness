<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\ActivityLog;

class ActivityLogObserver
{
    public function created(ActivityLog $log): void
    {
        $memberName = $log->member?->full_name ?? 'Unknown';

        AuditLog::record(
            action:      'activity_logged',
            details:     "Member {$memberName} mencatat aktivitas: {$log->steps} langkah, {$log->calories_burned} kkal terbakar.",
            targetTable: 'activity_logs',
            targetId:    $log->id,
        );
    }
}
