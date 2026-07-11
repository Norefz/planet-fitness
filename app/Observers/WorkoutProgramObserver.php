<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\WorkoutProgram;

class WorkoutProgramObserver
{
    public function created(WorkoutProgram $program): void
    {
        $mentorName = $program->mentor?->full_name ?? 'Unknown';

        AuditLog::record(
            action:      'program_created',
            details:     "Mentor {$mentorName} membuat program baru: \"{$program->title}\".",
            targetTable: 'workout_programs',
            targetId:    $program->id,
        );
    }

    public function updated(WorkoutProgram $program): void
    {
        // Cek apakah status berubah jadi published
        if ($program->wasChanged('status')) {
            $old = $program->getOriginal('status');
            $new = $program->status;

            if ($new === 'published') {
                $mentorName = $program->mentor?->full_name ?? 'Unknown';
                AuditLog::record(
                    action:      'program_published',
                    details:     "Mentor {$mentorName} mempublikasikan program: \"{$program->title}\".",
                    targetTable: 'workout_programs',
                    targetId:    $program->id,
                );
                return;
            }

            AuditLog::record(
                action:      'program_status_changed',
                details:     "Status program \"{$program->title}\" berubah dari {$old} → {$new}.",
                targetTable: 'workout_programs',
                targetId:    $program->id,
            );
            return;
        }

        AuditLog::record(
            action:      'program_updated',
            details:     "Program \"{$program->title}\" diperbarui.",
            targetTable: 'workout_programs',
            targetId:    $program->id,
        );
    }

    public function deleted(WorkoutProgram $program): void
    {
        AuditLog::record(
            action:      'program_deleted',
            details:     "Program \"{$program->title}\" dihapus.",
            targetTable: 'workout_programs',
            targetId:    $program->id,
        );
    }
}
