<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\MealLog;

class MealLogObserver
{
    public function created(MealLog $log): void
    {
        $memberName = $log->member?->full_name ?? 'Unknown';

        AuditLog::record(
            action:      'meal_logged',
            details:     "Member {$memberName} mencatat log nutrisi: {$log->food_name} ({$log->calories} kkal).",
            targetTable: 'meal_logs',
            targetId:    $log->id,
        );
    }

    public function deleted(MealLog $log): void
    {
        $memberName = $log->member?->full_name ?? 'Unknown';

        AuditLog::record(
            action:      'meal_log_deleted',
            details:     "Member {$memberName} menghapus log nutrisi: {$log->food_name}.",
            targetTable: 'meal_logs',
            targetId:    $log->id,
        );
    }
}
