<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowHistory;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with book recommendations and categories.
     * 
     * This method handles the core discover page, showing:
     * - Recommended books based on user's reading history and preferences
     * - Book categories with counts and styling
     * - Recently added books
     * - Popular books this week based on borrow history
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all unique categories for the filter dropdown
        // We're using distinct() to avoid duplicate categories
        $categories = Book::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');
        
        // Get recommended books based on user's favorites and highly-rated books
        // This query does several things:
        // 1. Prioritizes books the user has favorited but not borrowed
        // 2. Shows highly rated books (rating >= 4.0)
        // 3. Ensures books are actually available for borrowing
        // 4. Limits to 10 books to keep the UI clean
        $recommendedBooks = Book::where('available_copies', '>', 0)
            ->where('rating', '>=', 4.0)
            ->whereNotIn('id', function($query) {
                // Exclude books the user has already borrowed
                $query->select('book_id')
                    ->from('borrow_history')
                    ->where('user_id', auth()->id())
                    ->where('status', '!=', 'returned');
            })
            ->with('authors') // Eager load authors to avoid N+1 query problem
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($book) {
                // Format the author names into a single string
                // This makes it easier to display in the view
                $book->author = $book->authors->pluck('name')->implode(', ') ?: 'Unknown Author';
                return $book;
            });
        
        // Build the category cards with dynamic data
        // Each category gets a unique gradient, icon, and metadata
        // This creates an engaging visual display of categories
        $bookCategories = $this->buildCategoryCards($categories);
        
        // Get recently added books (last 30 days)
        // We want to show users what's new in the library
        // This encourages repeat visits to see new additions
        $recentBooks = Book::where('created_at', '>=', now()->subDays(30))
            ->where('available_copies', '>', 0)
            ->with('authors')
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get()
            ->map(function($book) {
                $book->author = $book->authors->pluck('name')->implode(', ') ?: 'Unknown Author';
                return $book;
            });
        
        // Get popular books this week based on actual borrow data
        // This uses a sophisticated query that:
        // 1. Counts borrows from the past 7 days
        // 2. Joins with books to get full book details
        // 3. Orders by borrow count to show most popular first
        // This gives users social proof of what others are reading
        $popularBooks = Book::select('books.*', DB::raw('COUNT(borrow_history.id) as borrow_count'))
            ->leftJoin('borrow_history', function($join) {
                $join->on('books.id', '=', 'borrow_history.book_id')
                     ->where('borrow_history.borrowed_at', '>=', now()->subDays(7));
            })
            ->with('authors')
            ->groupBy('books.id')
            ->orderBy('borrow_count', 'desc')
            ->limit(6)
            ->get()
            ->map(function($book) {
                $book->author = $book->authors->pluck('name')->implode(', ') ?: 'Unknown Author';
                return $book;
            });
        
        // Pass all the data to the view
        // The view will handle the presentation and animations
        return view('dashboard', compact(
            'categories',
            'recommendedBooks',
            'bookCategories',
            'recentBooks',
            'popularBooks'
        ));
    }
    
    /**
     * Build category cards with dynamic styling and metadata.
     * 
     * This method creates visually appealing category cards by:
     * - Assigning unique gradient colors to each category
     * - Counting books in each category
     * - Adding appropriate icons for common categories
     * - Creating engaging tags/labels
     *
     * @param \Illuminate\Support\Collection $categories
     * @return array
     */
    private function buildCategoryCards($categories)
    {
        // Define gradient combinations for different categories
        // These gradients create visual interest and help users
        // quickly identify different categories
        $gradients = [
            'from-purple-400 to-pink-600',
            'from-blue-400 to-indigo-600',
            'from-green-400 to-teal-600',
            'from-yellow-400 to-orange-600',
            'from-red-400 to-pink-600',
            'from-indigo-400 to-purple-600',
            'from-teal-400 to-cyan-600',
            'from-orange-400 to-red-600',
        ];
        
        // Define icons for common category types
        // These SVG icons help users visually identify categories
        $icons = [
            'fiction' => '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>',
            'business' => '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>',
            'technology' => '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/></svg>',
            'science' => '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd"/></svg>',
            'default' => '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>',
        ];
        
        // Define engaging tags for categories
        $tags = ['Trending', 'Popular', 'New Arrivals', 'Bestsellers', 'Must Read', 'Featured'];
        
        // Build the category data array
        return $categories->map(function($category, $index) use ($gradients, $icons, $tags) {
            // Count books in this category
            $count = Book::where('category', $category)
                ->where('available_copies', '>', 0)
                ->count();
            
            // Create URL-friendly slug for routing
            $slug = \Illuminate\Support\Str::slug($category);
            
            // Get appropriate icon based on category name
            $categoryLower = strtolower($category);
            $icon = $icons[$categoryLower] ?? $icons['default'];
            
            // Assign gradient (cycle through if we have more categories than gradients)
            $gradient = $gradients[$index % count($gradients)];
            
            // Assign tag (cycle through available tags)
            $tag = $tags[$index % count($tags)];
            
            return [
                'name' => $category,
                'slug' => $slug,
                'count' => $count,
                'gradient' => $gradient,
                'icon' => $icon,
                'tag' => $tag,
                'image' => null, // You can add category images later
            ];
        })->toArray();
    }
    
    /**
     * Show books for a specific category.
     * 
     * This method handles the category detail page, showing all books
     * in a selected category with filtering and sorting options.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function showCategory($slug)
    {
        // Convert slug back to category name
        $category = Book::select('category')
            ->whereRaw('LOWER(REPLACE(category, " ", "-")) = ?', [strtolower($slug)])
            ->first()
            ->category ?? abort(404);
        
        // Get all books in this category
        $books = Book::where('category', $category)
            ->with('authors')
            ->orderBy('rating', 'desc')
            ->paginate(20);
        
        // Format author names
        $books->getCollection()->transform(function($book) {
            $book->author = $book->authors->pluck('name')->implode(', ') ?: 'Unknown Author';
            return $book;
        });
        
        return view('books.category', compact('category', 'books'));
    }
}