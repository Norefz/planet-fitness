<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',       // member | mentor | super_admin
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

    // ─── Relationships ────────────────────────────────────────────────────────

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    // ← Tambahan: relasi ke tabel super_admins
    public function superAdmin()
    {
        return $this->hasOne(SuperAdmin::class, 'user_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isMember(): bool      { return $this->role === 'member'; }
    public function isMentor(): bool      { return $this->role === 'mentor'; }
    public function isSuperAdmin(): bool  { return $this->role === 'super_admin'; }
    public function isGoogleUser(): bool  { return ! is_null($this->google_id); }

    public function mentorProfile(): Mentor
    {
        $mentor = $this->mentor()->first();

        if ($mentor) {
            return $mentor;
        }

        return $this->mentor()->create([
            'user_id'        => $this->id,
            'full_name'      => $this->name ?: ($this->email ? explode('@', $this->email)[0] : 'Mentor'),
            'bio'            => null,
            'certification'  => null,
            'specialization' => null,
            'is_verified'    => false,
        ]);
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'member'      => 'member.dashboard',
            'mentor'      => 'mentor.dashboard',
            'super_admin' => 'admin.dashboard',
            default       => '/',
        };
    }

    // ← Tambahan: URL foto profil yang dipakai di navbar.
    // Member yang sudah mengunggah foto profil sendiri (via Cloudinary) akan
    // memakai foto itu; selain itu fallback ke foto Google (kolom avatar).
    public function getAvatarUrlAttribute(): ?string
    {
        return match ($this->role) {
            'member' => $this->member?->profile_photo_url ?: $this->avatar,
            'mentor' => $this->mentor?->profile_photo_url ?: $this->avatar,
            default  => $this->avatar,
        };
    }

    // ← Tambahan: nama display yang dipakai di navbar & sidebar admin
    // Mengambil full_name dari tabel profil sesuai role,
    // fallback ke kolom name di tabel users.
    public function getDisplayNameAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => $this->superAdmin?->full_name,
            'mentor'      => $this->mentor?->full_name,
            default       => $this->member?->full_name,
        } ?? $this->name ?? explode('@', $this->email)[0];
    }
}
