<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',          // member | mentor | super_admin
        'google_id',
        'avatar',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isMember(): bool      { return $this->role === 'member'; }
    public function isMentor(): bool      { return $this->role === 'mentor'; }
    public function isSuperAdmin(): bool  { return $this->role === 'super_admin'; }
    public function isGoogleUser(): bool  { return ! is_null($this->google_id); }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'member'      => 'member.dashboard',
            'mentor'      => 'mentor.dashboard',
            'super_admin' => 'admin.dashboard',
            default       => '/',
        };
    }
}
