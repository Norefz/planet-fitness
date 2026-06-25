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
}
