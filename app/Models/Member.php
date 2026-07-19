<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Member extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'gender',
        'height_cm',
        'weight_kg',
        'phone',
        'profile_photo_url',
        'subscription_type',
        'subscription_expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(WorkoutEnrollment::class);
    }

    // ← Tambahan: relasi ke bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Inisial nama untuk avatar bulat di UI (mis. "Budi Santoso" -> "BS").
     */
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->full_name));
        $letters = array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: 'M';
    }
}
