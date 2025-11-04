<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $table = 'favourites';

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    public $timestamps = false;

    /* ========================
       Relationships
    ======================== */

    // Each favourite belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each favourite belongs to a book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
