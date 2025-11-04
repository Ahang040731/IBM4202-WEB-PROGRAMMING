<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookAuthor extends Pivot
{
    protected $table = 'book_authors';

    protected $fillable = [
        'book_id',
        'author_id',
        'author_order',
        'role',
    ];

    /* ========================
       Relationships
    ======================== */

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}