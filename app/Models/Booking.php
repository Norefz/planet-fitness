<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasUuids;

    protected $fillable = [
        'member_id',
        'mentor_id',
        'topic',
        'scheduled_at',
        'duration_minutes',
        'status',
        'meeting_url',      // join_url untuk member
        'zoom_meeting_id',  // ID Zoom untuk hapus meeting saat cancel
        'zoom_start_url',   // start_url untuk mentor (host)
        'cancellation_reason',
        'mentor_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'confirmed')->where('scheduled_at', '>=', now());
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->status),
        };
    }

    // Cek apakah meeting Zoom sudah dibuat
    public function hasZoomMeeting(): bool
    {
        return ! empty($this->zoom_meeting_id);
    }
}
