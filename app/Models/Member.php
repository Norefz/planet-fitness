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
        'profile_photo_public_id',
        'subscription_type',
        'subscription_expires_at',
    ];

    // ← Tambahan: cast agar birth_date jadi instance Carbon (dibutuhkan
    // untuk ->format() di halaman Profil Saya), bukan string mentah dari DB.
    protected $casts = [
        'birth_date' => 'date',
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
}
