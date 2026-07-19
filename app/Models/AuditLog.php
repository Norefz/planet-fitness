<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AuditLog extends Model
{
    use HasUuids;

    protected $table      = 'audit_logs';
    public    $timestamps = false;  // tabel tidak punya created_at/updated_at

    protected $fillable = [
        'admin_id',
        'action',
        'target_table',
        'target_id',
        'details',
        'ip_address',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    // Accessor supaya view bisa pakai $log->created_at maupun $log->performed_at
    public function getCreatedAtAttribute()
    {
        return $this->performed_at;
    }

    // ── Relasi ke SuperAdmin ──────────────────────────────────────────────────
    public function admin()
    {
        // admin_id FK langsung ke super_admins.id (sesuai ERD)
        return $this->belongsTo(SuperAdmin::class, 'admin_id');
    }

    // ── Static helper: catat log dari mana saja ──────────────────────────────
    public static function record(
        string  $action,
        string  $details,
        ?string $targetTable = null,
        ?string $targetId    = null,
    ): void {
        // PERBAIKAN: guard 'admin' provider-nya adalah App\Models\User (lihat
        // config/auth.php), BUKAN SuperAdmin — jadi auth('admin')->id() itu
        // users.id, sedangkan audit_logs.admin_id FK ke super_admins.id.
        // Itu sebabnya insert selalu gagal (1452 foreign key constraint).
        // Ambil SuperAdmin-nya lewat relasi, persis seperti di Admin\AuthController.
        $adminId = auth('admin')->user()?->superAdmin?->id;

        static::create([
            'admin_id'     => $adminId,
            'action'       => $action,
            'target_table' => $targetTable,
            'target_id'    => $targetId,
            'details'      => $details,
            'ip_address'   => request()->ip(),
            'performed_at' => now(),
        ]);
    }
}
