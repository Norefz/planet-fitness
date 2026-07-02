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
     * Inisial nama untuk avatar bulat di UI (mis. "Rini Andini" -> "RA").
     */
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->full_name));
        $letters = array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: 'M';
    }
}
