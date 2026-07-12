<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkoutProgram extends Model
{
    use HasUuids;

    protected $fillable = [
        'mentor_id',
        'title',
        'category',
        'level',
        'description',
        'duration_weeks',
        'sessions_per_week',
        'sets',
        'reps',
        'video_url',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function enrollments()
    {
        return $this->hasMany(WorkoutEnrollment::class);
    }

    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class)->orderBy('order_index');
    }

    public function exerciseCount(): int
    {
        return $this->relationLoaded('exercises')
            ? $this->exercises->count()
            : $this->exercises()->count();
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    // ─── Accessors / Helpers ─────────────────────────────────────────────────

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function levelLabel(): string
    {
        return match ($this->level) {
            'pemula'   => 'Pemula',
            'menengah' => 'Menengah',
            'lanjutan' => 'Lanjutan',
            default    => ucfirst($this->level),
        };
    }

    /**
     * Jumlah member yang sedang mengambil program ini.
     */
    public function enrolledCount(): int
    {
        return $this->relationLoaded('enrollments')
            ? $this->enrollments->count()
            : $this->enrollments()->count();
    }

    /**
     * Rata-rata progres (0-100) seluruh member pada program ini.
     * Mengembalikan null jika belum ada member yang mengambil program.
     */
    public function averageProgress(): ?float
    {
        if ($this->relationLoaded('enrollments')) {
            return $this->enrollments->isEmpty() ? null : round($this->enrollments->avg('progress_pct'), 1);
        }

        $avg = $this->enrollments()->avg('progress_pct');

        return is_null($avg) ? null : round($avg, 1);
    }

    /**
     * Persentase member yang sudah menyelesaikan program ini (0-100).
     */
    public function completionRate(): ?float
    {
        $total = $this->enrolledCount();

        if ($total === 0) {
            return null;
        }

        $completed = $this->relationLoaded('enrollments')
            ? $this->enrollments->where('status', 'completed')->count()
            : $this->enrollments()->completed()->count();

        return round(($completed / $total) * 100, 1);
    }

    /**
     * Warna & ikon tampilan berdasarkan kategori — murni untuk keperluan UI.
     */
    public function themeColor(): array
    {
        return match (true) {
            str_contains(strtolower($this->category), 'fat')     => ['from' => '#1d9e75', 'to' => '#0f6e56', 'icon' => 'flame'],
            str_contains(strtolower($this->category), 'kuat')    => ['from' => '#378add', 'to' => '#185fa5', 'icon' => 'barbell'],
            str_contains(strtolower($this->category), 'core')    => ['from' => '#d4537e', 'to' => '#be185d', 'icon' => 'target'],
            str_contains(strtolower($this->category), 'kardio')  => ['from' => '#f59e0b', 'to' => '#b45309', 'icon' => 'heart'],
            str_contains(strtolower($this->category), 'fleksi')  => ['from' => '#8b5cf6', 'to' => '#6d28d9', 'icon' => 'stretch'],
            default => ['from' => '#64748b', 'to' => '#334155', 'icon' => 'dumbbell'],
        };
    }
}
