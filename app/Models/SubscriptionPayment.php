<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasUuids;

    protected $fillable = [
        'member_id',
        'order_id',
        'amount',
        'status',
        'snap_token',
        'payment_type',
        'paid_at',
        'gateway_payload',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'gateway_payload' => 'array',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
