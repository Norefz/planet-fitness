<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperAdmin extends Authenticatable
{
    use Notifiable;

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

    // ── Auth: pakai email & password dari tabel users ──
    // Guard 'admin' dikonfigurasi di config/auth.php menggunakan tabel users
    // dengan scope role = 'super_admin', tapi kita simpan profil di super_admins.
    //
    // Karena Authenticatable perlu kolom langsung, kita override getAuthIdentifierName
    // agar guard bisa kerja langsung dari model ini dengan kolom dari users.
    //
    // Alternatif lebih clean: langsung gunakan User model dengan guard admin.
    // Di sini kita pilih User model untuk guard admin (lihat config/auth.php).
}
