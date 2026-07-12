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
}
