<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
     public $timestamps = false; // we only store created_at
    protected $fillable = ['user_id','delta','reason','method','reference'];

    protected $casts = [
        'delta' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeTopups($q){
        return $q->where('delta', '>', 0);
    }
    public function scopeDebits($q){
        return $q->where('delta', '<', 0);
    }
    public function scopeForReason($q, string $reason){
        return $q->where('reason', $reason);
    }
}
