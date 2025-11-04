<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'user_id',
        'borrowing_id',
        'reason',
        'amount',
        'status',
        'method',
        'transaction_ref',
        'paid_at',
        'handled_by',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowing()
    {
        return $this->belongsTo(BorrowHistory::class);
    }

    public function handler()
    {
        return $this->belongsTo(Admin::class, 'handled_by'); // updated to match FK
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // Helpers
    public function markPaid(string $method, ?string $ref = null, ?int $handlerId = null): void
    {
        $this->forceFill([
            'status'          => 'paid',
            'method'          => $method,
            'transaction_ref' => $ref,
            'paid_at'         => now(),
            'handled_by'      => $handlerId,
        ])->save();
    }
}