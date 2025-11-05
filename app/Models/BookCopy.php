<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $fillable = [
        'book_id',
        'status',
        'condition',
        'barcode',
    ];

    /* ========================
       Relationships
    ======================== */

    // Each copy belongs to one book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Optional if you have borrowing table
    // public function borrowings()
    // {
    //     return $this->hasMany(Borrowing::class, 'copy_id');
    // }

    /* ========================
       Scopes / Helpers
    ======================== */

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 'not available');
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }
}