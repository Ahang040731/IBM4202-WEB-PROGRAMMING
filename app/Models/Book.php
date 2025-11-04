<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Book Model
 * 
 * This model represents a book in the library system. It handles all book-related
 * functionality including relationships with authors, copies, borrow history,
 * and user favorites.
 * 
 * The model uses several relationships to connect books with:
 * - Authors (many-to-many through book_authors pivot table)
 * - Physical copies (one-to-many with book_copies)
 * - Borrow records (one-to-many with borrow_history)
 * - User favorites (many-to-many through favourites)
 * 
 * Key features:
 * - Automatic photo URL generation for book covers
 * - Availability checking (whether any copies are available)
 * - Rating calculations
 * - Author name formatting
 */
class Book extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     * 
     * Laravel will automatically assume the table name is 'books' (plural of model name),
     * but we're explicitly defining it here for clarity and to avoid any confusion.
     * 
     * @var string
     */
    protected $table = 'books';
    
    /**
     * The attributes that are mass assignable.
     * 
     * Mass assignment is a convenient way to create or update model records.
     * By listing attributes here, we allow them to be set via methods like
     * create() or update() while protecting against malicious input.
     * 
     * For example: Book::create(['book_name' => 'Harry Potter', ...])
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'book_name',          // The title of the book
        'photo',              // Path to the book cover image
        'author',             // Primary author (deprecated, use authors relationship instead)
        'published_year',     // Year the book was published
        'description',        // Book synopsis or description
        'rating',             // Average user rating (0-5 scale)
        'category',           // Book category (Fiction, Business, etc.)
        'total_copies',       // Total number of physical copies owned
        'available_copies',   // Number of copies currently available for borrowing
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * Casting ensures that when we retrieve data from the database, it's automatically
     * converted to the correct PHP type. This is especially useful for:
     * - Dates (Carbon instances for easy manipulation)
     * - Numbers (ensuring calculations work correctly)
     * - Booleans (true/false instead of 1/0)
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'published_year' => 'integer',      // Year as number, not string
        'rating' => 'decimal:2',             // Rating with 2 decimal places (4.50)
        'total_copies' => 'integer',         // Count as number
        'available_copies' => 'integer',     // Count as number
        'created_at' => 'datetime',          // Carbon instance for date manipulation
        'updated_at' => 'datetime',          // Carbon instance for date manipulation
    ];
    
    /**
     * Get the authors associated with this book.
     * 
     * This is a many-to-many relationship because:
     * - One book can have multiple authors (co-authors)
     * - One author can write multiple books
     * 
     * The relationship goes through the 'book_authors' pivot table which stores
     * additional information like author_order (1st author, 2nd author, etc.)
     * and role (Author, Editor, Illustrator, etc.)
     * 
     * Usage example:
     * $book->authors // Returns collection of Author models
     * $book->authors->pluck('name') // Get array of author names
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id')
            ->withPivot('author_order', 'role')  // Include these extra pivot columns
            ->withTimestamps()                   // Track when relationships are created
            ->orderBy('book_authors.author_order', 'asc'); // Order authors by their position
    }
    
    /**
     * Get all physical copies of this book.
     * 
     * This is a one-to-many relationship because:
     * - One book (the title) can have many physical copies
     * - Each copy belongs to only one book
     * 
     * Each copy has its own status (available/borrowed) and condition (new/good/worn),
     * and can be tracked individually using barcodes.
     * 
     * Usage example:
     * $book->copies // All copies
     * $book->copies()->where('status', 'available')->get() // Available copies only
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class, 'book_id');
    }
    
    /**
     * Get the borrow history for this book.
     * 
     * This tracks every time this book (any copy) has been borrowed.
     * It's useful for:
     * - Viewing borrowing statistics
     * - Identifying popular books
     * - Tracking overdue books
     * - Calculating fines
     * 
     * Usage example:
     * $book->borrowHistory()->where('status', 'active')->count() // Currently borrowed copies
     * $book->borrowHistory()->where('status', 'overdue')->get() // Overdue borrows
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowHistory(): HasMany
    {
        return $this->belongsToMany(Author::class, 'books_author')
                    ->using(BookAuthor::class)  // tell Laravel to use your custom pivot model
                    ->withPivot(['author_order', 'role'])
                    ->withTimestamps()
                    ->orderBy('books_author.author_order');
    }


    /* ========================
       Accessors / Helpers
    ======================== */

    public function addCopies(int $count, string $barcodePrefix = null, int $start = 1, string $status = 'available', string $condition = 'good'): void
    {
        $barcodePrefix = $barcodePrefix ?? strtoupper(preg_replace('/[^A-Z0-9]+/i', '-', $this->book_name));

        for ($i = 0; $i < $count; $i++) {
            \App\Models\BookCopy::create([
                'book_id'   => $this->id,
                'status'    => $status,
                'condition' => $condition,
                'barcode'   => $barcodePrefix . '-' . str_pad($start + $i, 3, '0', STR_PAD_LEFT),
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }

        // refresh counters
        $this->update([
            'total_copies'      => $this->copies()->count(),
            'available_copies'  => $this->copies()->where('status','available')->count(),
        ]);
    }


    // Returns true if the book still has copies to borrow
    public function isAvailable()
    {
        return $this->available_copies > 0;
    }
    
    /**
     * Get a formatted author string.
     * 
     * This combines all authors associated with the book into a single
     * comma-separated string, which is useful for displaying in views.
     * 
     * For example: "J.K. Rowling" or "Neil Gaiman, Terry Pratchett"
     * 
     * Usage: $book->author_names
     * 
     * @return string
     */
    public function getAuthorNamesAttribute(): string
    {
        // If the book has authors through the relationship, use those
        if ($this->authors && $this->authors->count() > 0) {
            return $this->authors->pluck('name')->implode(', ');
        }
        
        // Fall back to the legacy author column if it exists
        if ($this->author) {
            return $this->author;
        }
        
        // Default if no author information is available
        return 'Unknown Author';
    }
    
    /**
     * Get the number of times this book has been borrowed.
     * 
     * This counts all borrow records for this book, regardless of status.
     * It's useful for popularity metrics and statistics.
     * 
     * Usage: $book->borrow_count
     * 
     * @return int
     */
    public function getBorrowCountAttribute(): int
    {
        return $this->borrowHistory()->count();
    }
    
    /**
     * Check if the current user has favorited this book.
     * 
     * This is a helper method that checks if the authenticated user
     * has added this book to their favorites. It's useful for showing
     * the correct heart icon (filled vs outline) in the UI.
     * 
     * Usage:
     * if ($book->is_favorited_by_user) {
     *     // Show filled heart icon
     * }
     * 
     * @return bool
     */
    public function getIsFavoritedByUserAttribute(): bool
    {
        // If no user is authenticated, the book can't be favorited
        if (!auth()->check()) {
            return false;
        }
        
        // Check if this book is in the user's favorites
        return $this->favoritedBy()
            ->where('user_id', auth()->id())
            ->exists();
    }
}