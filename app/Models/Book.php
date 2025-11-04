<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'book_name',
        'photo',
        'author',
        'published_year',
        'description',
        'rating',
        'category',
        'total_copies',
        'available_copies',
    ];

    /* ========================
       Relationships
    ======================== */

    // One book can have many copies
    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

        public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors')
                    ->using(BookAuthor::class)  // tell Laravel to use your custom pivot model
                    ->withPivot(['author_order', 'role'])
                    ->withTimestamps()
                    ->orderBy('book_authors.author_order');
    }


    /* ========================
       Accessors / Helpers
    ======================== */

    // Returns true if the book still has copies to borrow
    public function isAvailable()
    {
        return $this->available_copies > 0;
    }

    // Compute rating display (rounded)
    public function getRatingAttribute($value)
    {
        return round($value, 1);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function favouritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favourites')
                    ->withTimestamps();
    }

}