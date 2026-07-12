<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkoutExercise extends Model
{
    use HasUuids;

    protected $fillable = [
        'workout_program_id',
        'name',
        'description',
        'video_url',
        'sets',
        'reps',
        'duration_seconds',
        'rest_seconds',
        'order_index',
    ];

    protected $casts = [
        'sets'             => 'integer',
        'reps'             => 'integer',
        'duration_seconds' => 'integer',
        'rest_seconds'     => 'integer',
        'order_index'      => 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function workoutProgram()
    {
        return $this->belongsTo(WorkoutProgram::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order_index');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isTimeBased(): bool
    {
        return ! is_null($this->duration_seconds);
    }

    /**
     * Durasi dalam format ramah baca: "45 detik" atau "1:30" untuk >= 60 detik.
     */
    public function formattedDuration(): ?string
    {
        if (is_null($this->duration_seconds)) {
            return null;
        }

        if ($this->duration_seconds < 60) {
            return $this->duration_seconds . ' detik';
        }

        return floor($this->duration_seconds / 60) . ':' . str_pad($this->duration_seconds % 60, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Ringkasan singkat untuk ditampilkan di daftar, mis. "3 set × 12 reps" atau "3 set × 45 detik".
     */
    public function summaryLabel(): string
    {
        $main = null;

        if ($this->sets && $this->reps) {
            $main = $this->sets . ' set × ' . $this->reps . ' reps';
        } elseif ($this->sets && $this->formattedDuration()) {
            $main = $this->sets . ' set × ' . $this->formattedDuration();
        } elseif ($this->sets) {
            $main = $this->sets . ' set';
        } elseif ($this->reps) {
            $main = $this->reps . ' reps';
        } elseif ($this->formattedDuration()) {
            $main = $this->formattedDuration();
        }

        if (! $main) {
            return 'Belum ada detail';
        }

        return $this->rest_seconds ? $main . ' · istirahat ' . $this->rest_seconds . 'd' : $main;
    }
}
