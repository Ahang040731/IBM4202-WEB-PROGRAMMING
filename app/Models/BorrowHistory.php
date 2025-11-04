<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowHistory extends Model
{
    protected $table = 'borrow_history';

    protected $fillable = [
        'user_id',
        'book_id',
        'copy_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'extension_count',
        'extension_reason',
        'approve_status',
    ];

    // Automatically handle timestamps
    public $timestamps = true;

    /* ==========================
       Relationships
    =========================== */

    // Each borrowing record belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each borrowing record is tied to one book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Each borrowing record corresponds to one specific copy
    public function copy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id');
    }

    /* ==========================
       Scopes & Helpers
    =========================== */

    // Only active borrowings
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Only overdue borrowings
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    // Only returned borrowings
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    // Whether this borrowing is overdue
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || ($this->due_at < now() && is_null($this->returned_at));
    }

    // Days late (0 if not overdue)
    public function getLateDaysAttribute(): int
    {
        if ($this->returned_at && $this->returned_at->gt($this->due_at)) {
            return $this->returned_at->diffInDays($this->due_at);
        }

        if (is_null($this->returned_at) && $this->due_at->lt(now())) {
            return now()->diffInDays($this->due_at);
        }

        return 0;
    }

    // Check if book has been returned
    public function isReturned(): bool
    {
        return !is_null($this->returned_at);
    }
}
