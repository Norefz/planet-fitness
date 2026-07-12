<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkoutEnrollment extends Model
{
    use HasUuids;

    protected $fillable = [
        'member_id',
        'workout_program_id',
        'progress_pct',
        'status',
        'started_at',
        'last_activity_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_pct'      => 'integer',
        'started_at'        => 'datetime',
        'last_activity_at'  => 'datetime',
        'completed_at'      => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function workoutProgram()
    {
        return $this->belongsTo(WorkoutProgram::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('workout_enrollments.status', 'completed');
    }

    /**
     * Member yang belum selesai dan tidak menunjukkan aktivitas dalam >7 hari
     * (atau belum pernah beraktivitas sama sekali) — sinyal bagi mentor untuk
     * menyapa/menindaklanjuti member tersebut.
     */
    public function scopeNeedsAttention(Builder $query): Builder
    {
        return $query->where('workout_enrollments.status', '!=', 'completed')
            ->where(function ($q) {
                $q->whereNull('workout_enrollments.last_activity_at')
                  ->orWhere('workout_enrollments.last_activity_at', '<=', now()->subDays(7));
            });
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function needsAttention(): bool
    {
        return ! $this->isCompleted()
            && (is_null($this->last_activity_at) || $this->last_activity_at->lte(now()->subDays(7)));
    }
}
