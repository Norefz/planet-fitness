<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\MemberProgram;

class MemberProgramObserver
{
    public function created(MemberProgram $mp): void
    {
        $memberName  = $mp->member?->full_name ?? 'Unknown';
        $programName = $mp->workoutProgram?->title ?? 'Unknown';

        AuditLog::record(
            action:      'program_enrolled',
            details:     "Member {$memberName} mendaftar ke program: \"{$programName}\".",
            targetTable: 'member_programs',
            targetId:    $mp->id,
        );
    }

    public function updated(MemberProgram $mp): void
    {
        if (! $mp->wasChanged('status')) {
            return;
        }

        $memberName  = $mp->member?->full_name ?? 'Unknown';
        $programName = $mp->workoutProgram?->title ?? 'Unknown';
        $new         = $mp->status;

        $labelMap = [
            'completed' => "Member {$memberName} menyelesaikan program: \"{$programName}\".",
            'dropped'   => "Member {$memberName} keluar dari program: \"{$programName}\".",
        ];

        AuditLog::record(
            action:      "program_{$new}",
            details:     $labelMap[$new] ?? "Status program {$memberName} berubah: {$new}.",
            targetTable: 'member_programs',
            targetId:    $mp->id,
        );
    }
}
