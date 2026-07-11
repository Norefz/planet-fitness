<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table      = 'audit_logs';
    public    $timestamps = false;

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

    // ── Accessor agar bisa pakai $log->created_at di view ──
    public function getCreatedAtAttribute()
    {
        return $this->performed_at;
    }

    // ── Relasi ke admin yang melakukan aksi ──
    public function admin()
    {
        return $this->belongsTo(SuperAdmin::class, 'admin_id');
    }

    // ── Helper: catat log dengan mudah dari mana saja ──
    public static function record(
        string  $action,
        string  $details,
        ?string $targetTable = null,
        ?string $targetId    = null,
    ): void {
        $adminId = auth('admin')->check()
            ? auth('admin')->user()->superAdmin?->id
            : null;

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
