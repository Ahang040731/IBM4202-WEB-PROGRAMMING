<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BorrowHistory;
use App\Models\Favourite;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $account = auth()->user();

        if (!$account) {
            return redirect()->route('login')
                ->with('error', 'Please log in first.');
        }

        $user = $account->ensureUserProfile();

        if (!$user) {
            // e.g. admin hitting user profile page, or something weird
            abort(403, 'User profile not available for this account.');
        }

        // Get search and filter parameters
        $search = $request->input('search');
        $category = $request->input('category');
        $sort = $request->input('sort', 'newest');

        // Build query
        $query = Book::query();

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('book_name', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }

        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_year', 'asc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'title':
                $query->orderBy('book_name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('published_year', 'desc');
                break;
        }

        // Get paginated results
        $books = $query->paginate(12);

        // Check if books are favorited by current user
        if (auth()->check()) {
            $favoriteBookIds = Favourite::where('user_id', auth()->id())
                ->pluck('book_id')
                ->toArray();

            foreach ($books as $book) {
                $book->isFavorited = in_array($book->id, $favoriteBookIds);
            }
        }

        // Get statistics
        $totalBooks = Book::count();
        $borrowedCount = 0;
        $favoritesCount = 0;

        if (auth()->check()) {
            $borrowedCount = BorrowHistory::where('user_id', auth()->id())
                ->whereIn('status', ['active', 'overdue'])
                ->count();
            
            $favoritesCount = Favourite::where('user_id', auth()->id())->count();
        }

        return view('client.homepage.index', compact(
            'books',
            'totalBooks',
            'borrowedCount',
            'favoritesCount'
        ));
    }
}