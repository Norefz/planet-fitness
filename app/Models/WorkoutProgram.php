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
