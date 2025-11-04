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
        return $this->hasMany(BorrowHistory::class, 'book_id');
    }
    
    /**
     * Get users who have favorited this book.
     * 
     * This is a many-to-many relationship because:
     * - One book can be favorited by many users
     * - One user can favorite many books
     * 
     * The 'favourites' table acts as a pivot table storing these relationships.
     * This is useful for:
     * - Showing "X users favorited this" on book pages
     * - Generating recommendations based on favorites
     * - Notifying users when their favorited books become available
     * 
     * Usage example:
     * $book->favoritedBy // Collection of User models
     * $book->favoritedBy->contains(auth()->user()) // Check if current user favorited
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourites', 'book_id', 'user_id')
            ->withTimestamps(); // Track when users favorited the book
    }
    
    /**
     * Scope a query to only include available books.
     * 
     * Scopes are reusable query constraints. They make your code cleaner
     * and more readable. Instead of writing:
     * Book::where('available_copies', '>', 0)->get()
     * 
     * You can write:
     * Book::available()->get()
     * 
     * Scopes can be chained: Book::available()->popular()->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }
    
    /**
     * Scope a query to only include popular books.
     * 
     * Popular books are defined as those with a rating of 4.0 or higher.
     * You can adjust this threshold based on your needs.
     * 
     * Usage: Book::popular()->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->where('rating', '>=', 4.0)
            ->orderBy('rating', 'desc');
    }
    
    /**
     * Scope a query to filter by category.
     * 
     * This scope accepts a category parameter, making it easy to filter
     * books by category without writing the where clause each time.
     * 
     * Usage: Book::inCategory('Fiction')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    
    /**
     * Scope a query to search books by keyword.
     * 
     * This performs a search across multiple fields:
     * - Book name (title)
     * - Author (from the legacy author column)
     * - Description
     * 
     * The search is case-insensitive and matches partial words.
     * 
     * Usage: Book::search('harry potter')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('book_name', 'LIKE', "%{$keyword}%")
              ->orWhere('author', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }
    
    /**
     * Get the full URL for the book's photo.
     * 
     * Accessor methods allow you to modify attributes when retrieving them.
     * This accessor ensures that the photo attribute always returns a full URL,
     * not just a path, making it easier to use in views.
     * 
     * If no photo exists, it returns a placeholder image.
     * 
     * Usage: $book->photo_url
     * 
     * @return string
     */
    public function getPhotoUrlAttribute(): string
    {
        // If a photo exists, return the full URL
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        // Otherwise, return a placeholder image
        // You can customize this to use a service like placeholder.com or UI Avatars
        return "https://ui-avatars.com/api/?name=" . urlencode($this->book_name) . "&size=400&background=random";
    }
    
    /**
     * Check if the book is currently available for borrowing.
     * 
     * A book is available if at least one copy is not currently borrowed.
     * This is a simple boolean check that's useful in views and logic.
     * 
     * Usage: 
     * if ($book->is_available) {
     *     // Show "Borrow Now" button
     * }
     * 
     * @return bool
     */
    public function getIsAvailableAttribute(): bool
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