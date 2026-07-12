<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Mentor extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'full_name',
        'bio',
        'certification',
        'specialization',
        'rating',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workoutPrograms()
    {
        return $this->hasMany(WorkoutProgram::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Seluruh baris progres member, lintas semua program milik mentor ini
     * (Mentor -> WorkoutProgram -> WorkoutEnrollment). Dipakai untuk statistik agregat.
     */
    public function enrollments()
    {
        return $this->hasManyThrough(WorkoutEnrollment::class, WorkoutProgram::class);
    }

    /**
     * Jumlah member unik yang mengikuti minimal satu program milik mentor ini.
     */
    public function totalUniqueMembers(): int
    {
        return $this->enrollments()->distinct('member_id')->count('member_id');
    }

    /**
     * Rata-rata progres (0-100) seluruh member, di seluruh program. Null jika belum ada data.
     */
    public function overallAverageProgress(): ?float
    {
        $avg = $this->enrollments()->avg('progress_pct');

        return is_null($avg) ? null : round($avg, 1);
    }

    /**
     * Persentase enrollment yang berstatus selesai, di seluruh program. Null jika belum ada data.
     */
    public function overallCompletionRate(): ?float
    {
        $total = $this->enrollments()->count();

        if ($total === 0) {
            return null;
        }

        $completed = $this->enrollments()->where('workout_enrollments.status', 'completed')->count();

        return round(($completed / $total) * 100, 1);
    }

    /**
     * Jumlah member yang perlu ditindaklanjuti (belum selesai & tidak aktif >7 hari).
     */
    public function needsAttentionCount(): int
    {
        return $this->enrollments()->needsAttention()->count();
    }

    /**
     * Inisial nama untuk avatar bulat di UI (mis. "Rini Andini" -> "RA").
     */
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->full_name));
        $letters = array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: 'M';
    }
}
