<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SuperAdmin extends Authenticatable
{
    use Notifiable, HasUuids;

    protected $table = 'super_admins';

    protected $fillable = [
        'user_id',
        'full_name',
        'title',
        'employee_id',
        'is_head',
    ];

    protected $casts = [
        'is_head'    => 'boolean',
        'created_at' => 'datetime',
    ];

    // ── Relasi ke User ──
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
