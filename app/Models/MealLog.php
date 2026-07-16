<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MealLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'member_id',
        'food_name',
        'category',
        'log_date',
        'calories',
        'carbs_g',
        'protein_g',
        'fat_g',
    ];

    protected $casts = [
        'log_date'  => 'date',
        'calories'  => 'integer',
        'carbs_g'   => 'integer',
        'protein_g' => 'integer',
        'fat_g'     => 'integer',
    ];

    // ─── Relationships ──────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeForDate(Builder $query, $date): Builder
    {
        $value = $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : $date;

        return $query->whereDate('log_date', $value);
    }

    // ─── Presentation helpers (dipakai di Blade, biar view tetap bersih) ───

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'breakfast' => 'Sarapan',
            'lunch'     => 'Makan Siang',
            'dinner'    => 'Makan Malam',
            'snack'     => 'Snack',
            default     => ucfirst((string) $this->category),
        };
    }

    public function categoryIcon(): string
    {
        return match ($this->category) {
            'breakfast' => 'ti-bread',
            'lunch'     => 'ti-soup',
            'dinner'    => 'ti-fish',
            'snack'     => 'ti-glass-full',
            default     => 'ti-tools-kitchen-2',
        };
    }

    public function categoryColorClasses(): string
    {
        return match ($this->category) {
            'breakfast' => 'bg-emerald-50 text-emerald-600',
            'lunch'     => 'bg-blue-50 text-blue-600',
            'dinner'    => 'bg-rose-50 text-rose-600',
            'snack'     => 'bg-amber-50 text-amber-600',
            default     => 'bg-slate-100 text-slate-500',
        };
    }

    public static function categoryOptions(): array
    {
        return [
            'breakfast' => 'Sarapan',
            'lunch'     => 'Makan Siang',
            'dinner'    => 'Makan Malam',
            'snack'     => 'Snack',
        ];
    }
}
