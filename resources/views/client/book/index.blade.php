@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')

<div class="books-page-container">
    <!-- Hero Header -->
    <div class="page-hero">
        <div class="hero-background">
            <div class="hero-bg-image" style="background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1920&q=80')"></div>
            <div class="hero-overlay"></div>
            <div class="floating-books">
                <div class="floating-book" style="--delay: 0s">📚</div>
                <div class="floating-book" style="--delay: 1s">📖</div>
                <div class="floating-book" style="--delay: 2s">📕</div>
                <div class="floating-book" style="--delay: 1.5s">📗</div>
                <div class="floating-book" style="--delay: 0.5s">📘</div>
            </div>
        </div>

        <div class="hero-content">
            <h1 class="page-title animate-fade-in-up">
                <span class="title-gradient">Browse Our</span><br>
                <span class="title-highlight">Collection</span>
            </h1>
            <p class="page-subtitle animate-fade-in-up-delay">
                Discover and borrow books from our extensive library collection
            </p>
        </div>
    </div>

    <!-- Search and Filters Panel -->
    <div class="filters-section">
        <div class="search-filter-panel glass-effect animate-scale-in">
            <form method="GET" action="{{ route('client.books.index') }}" class="filter-form">
                <!-- Main Search -->
                <div class="search-main">
                    <div class="search-input-wrapper">
                        <div class="search-icon-wrapper">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="search-input"
                            placeholder="Search by title, author, or description..."
                        >
                        <button type="submit" class="search-btn-inline">
                            <span>Search</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Filters Grid -->
                <div class="filter-grid">
                    <div class="filter-item">
                        <label class="filter-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Category
                        </label>
                        <select name="category" class="filter-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Availability
                        </label>
                        <select name="available" class="filter-select">
                            <option value="">All Books</option>
                            <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Available Only</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                            </svg>
                            Sort By
                        </label>
                        <select name="sort" class="filter-select">
                            <option value="book_name" {{ request('sort') == 'book_name' ? 'selected' : '' }}>Title A-Z</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label" style="opacity: 0;">Actions</label>
                        <a href="{{ route('client.books.index') }}" class="clear-filters-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Info Bar -->
    <div class="results-bar">
        <div class="results-info glass-effect">
            <div class="results-count">
                <span class="count-highlight">{{ $books->total() }}</span> books found
                @if(request('search') || request('category') || request('available'))
                    <span class="filter-indicator">• Filtered</span>
                @endif
            </div>
            <a href="{{ route('client.cart.index') }}" class="cart-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Cart</span>
                <span class="cart-badge">{{ auth()->user()->user->carts()->count() }}</span>
            </a>
        </div>
    </div>

    <!-- Books Grid Section -->
    <div class="books-section">
        @if($books->count() > 0)
        <div class="books-grid">
            @foreach($books as $index => $book)
            <div class="book-card glass-effect animate-book-reveal" style="--delay: {{ ($index % 12) * 0.05 }}s">
                <div class="book-image-container">
                    @if($book->photo)
                        <img src="{{ $book->photo }}" alt="{{ $book->book_name }}" class="book-image" loading="lazy">
                    @else
                        <div class="book-placeholder">
                            <div class="placeholder-content">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>{{ Str::limit($book->book_name, 20) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="book-overlay"></div>

                    <!-- Status Badge -->
                    <div class="book-status {{ $book->available_copies > 0 ? 'available' : 'unavailable' }}">
                        <span class="status-dot"></span>
                        <span>{{ $book->available_copies > 0 ? 'Available' : 'Out of Stock' }}</span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <a href="{{ route('client.books.show', $book) }}" class="quick-action-btn" aria-label="Quick view">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="book-details">
                    <div class="book-category-badge">
                        <span class="category-icon">
                            @switch($book->category)
                                @case('Fiction') 📖 @break
                                @case('Science') 🔬 @break
                                @case('History') 📜 @break
                                @case('Technology') 💻 @break
                                @case('Fantasy') 🧙 @break
                                @case('Mystery') 🔍 @break
                                @default 📚
                            @endswitch
                        </span>
                        <span>{{ $book->category }}</span>
                    </div>

                    <h3 class="book-title" title="{{ $book->book_name }}">
                        {{ Str::limit($book->book_name, 35) }}
                    </h3>

                    <p class="book-author">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $book->author }}
                    </p>

                    <div class="book-meta">
                        <div class="book-rating">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="star {{ $i <= ($book->rating ?? 0) ? 'filled' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="rating-value">{{ number_format($book->rating ?? 0, 1) }}</span>
                        </div>

                        <div class="book-copies">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>{{ $book->available_copies }}/{{ $book->total_copies }}</span>
                        </div>
                    </div>

                    <div class="book-actions">
                        <a href="{{ route('client.books.show', $book) }}" class="view-details-btn">
                            <span>View Details</span>
                            <svg class="arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>

                        @if(in_array($book->id, $cartBookIds))
                            <button class="add-cart-btn in-cart" disabled>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                        @elseif($book->available_copies > 0)
                            <form action="{{ route('client.cart.store', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="add-cart-btn" title="Add to Cart">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <button class="add-cart-btn unavailable" disabled>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $books->links() }}
        </div>
        @else
        <div class="empty-state glass-effect">
            <div class="empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="empty-title">No books found</h3>
            <p class="empty-subtitle">Try adjusting your search criteria or filters</p>
            <a href="{{ route('client.books.index') }}" class="reset-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset Filters
            </a>
        </div>
        @endif
    </div>
</div>

<style>
/* ==================== BASE STYLES ==================== */
.books-page-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

/* ==================== PAGE HERO ==================== */
.page-hero {
    position: relative;
    min-height: 50vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px 20px 60px;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.hero-bg-image {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    animation: kenburns 20s ease-in-out infinite alternate;
}

@keyframes kenburns {
    0% { transform: scale(1) translateY(0); }
    100% { transform: scale(1.1) translateY(-10px); }
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(102, 126, 234, 0.95) 0%,
        rgba(118, 75, 162, 0.92) 50%,
        rgba(102, 126, 234, 0.95) 100%);
    backdrop-filter: blur(2px);
}

.floating-books {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.floating-book {
    position: absolute;
    font-size: 2.5rem;
    opacity: 0.12;
    animation: float 15s ease-in-out infinite;
    animation-delay: var(--delay, 0s);
}

.floating-book:nth-child(1) { top: 10%; left: 10%; }
.floating-book:nth-child(2) { top: 20%; right: 15%; }
.floating-book:nth-child(3) { bottom: 20%; left: 20%; }
.floating-book:nth-child(4) { bottom: 15%; right: 10%; }
.floating-book:nth-child(5) { top: 50%; left: 5%; }

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-30px) rotate(5deg); }
    50% { transform: translateY(-20px) rotate(-5deg); }
    75% { transform: translateY(-40px) rotate(3deg); }
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.page-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    color: white;
    margin-bottom: 16px;
    line-height: 1.2;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.title-gradient {
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.title-highlight {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    font-size: clamp(1rem, 2vw, 1.125rem);
    color: rgba(255, 255, 255, 0.95);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* ==================== FILTERS SECTION ==================== */
.filters-section {
    max-width: 1400px;
    margin: -40px auto 40px;
    padding: 0 20px;
    position: relative;
    z-index: 3;
}

.search-filter-panel {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.filter-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.search-main {
    position: relative;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.search-input-wrapper:focus-within {
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}

.search-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 20px;
}

.search-icon {
    width: 22px;
    height: 22px;
    color: #667eea;
}

.search-input {
    flex: 1;
    padding: 16px 0;
    border: none;
    font-size: 1rem;
    color: #1f2937;
    background: transparent;
}

.search-input:focus {
    outline: none;
}

.search-input::placeholder {
    color: #9ca3af;
}

.search-btn-inline {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.search-btn-inline:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
    transform: translateX(2px);
}

.search-btn-inline svg {
    width: 18px;
    height: 18px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.filter-label svg {
    width: 16px;
    height: 16px;
    color: #667eea;
}

.filter-select {
    padding: 12px 14px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9375rem;
    color: #1f2937;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.clear-filters-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 14px;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 12px;
    text-decoration: none;
    font-size: 0.9375rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.clear-filters-btn:hover {
    background: #e5e7eb;
    border-color: #d1d5db;
    color: #374151;
}

.clear-filters-btn svg {
    width: 18px;
    height: 18px;
}

/* ==================== RESULTS BAR ==================== */
.results-bar {
    max-width: 1400px;
    margin: 0 auto 32px;
    padding: 0 20px;
}

.results-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.results-count {
    font-size: 1rem;
    color: #374151;
    font-weight: 600;
}

.count-highlight {
    color: #667eea;
    font-weight: 800;
    font-size: 1.125rem;
}

.filter-indicator {
    color: #10b981;
    font-size: 0.875rem;
    margin-left: 8px;
}

.cart-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.cart-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.cart-link svg {
    width: 20px;
    height: 20px;
}

.cart-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    padding: 0 6px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
}

/* ==================== BOOKS SECTION ==================== */
.books-section {
    max-width: 1400px;
    margin: 0 auto 60px;
    padding: 0 20px;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 28px;
    margin-bottom: 48px;
}

.book-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.book-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
}

.book-image-container {
    position: relative;
    padding-top: 140%;
    overflow: hidden;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
}

.book-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.book-card:hover .book-image {
    transform: scale(1.1);
}

.book-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.book-card:hover .book-overlay {
    opacity: 1;
}

.book-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.placeholder-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 20px;
    text-align: center;
}

.placeholder-content svg {
    width: 70px;
    height: 70px;
    color: rgba(255, 255, 255, 0.5);
}

.placeholder-content span {
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

.book-status {
    position: absolute;
    top: 14px;
    right: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 2;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.8; }
}

.book-status.available {
    background: rgba(16, 185, 129, 0.95);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.book-status.available .status-dot {
    background: #ffffff;
}

.book-status.unavailable {
    background: rgba(239, 68, 68, 0.95);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.book-status.unavailable .status-dot {
    background: #ffffff;
}

.quick-actions {
    position: absolute;
    bottom: 80px;
    right: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    z-index: 2;
}

.book-card:hover .quick-actions {
    opacity: 1;
    transform: translateX(0);
}

.quick-action-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    text-decoration: none;
}

.quick-action-btn:hover {
    transform: scale(1.15);
    background: #667eea;
    color: white;
}

.quick-action-btn svg {
    width: 20px;
    height: 20px;
}

.book-details {
    padding: 22px;
}

.book-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
    color: #7c3aed;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    margin-bottom: 12px;
    border: 1px solid rgba(124, 58, 237, 0.2);
}

.category-icon {
    font-size: 0.9rem;
}

.book-title {
    font-size: 1.0625rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
    line-height: 1.4;
    min-height: 48px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.book-author {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 14px;
}

.book-author svg {
    width: 15px;
    height: 15px;
}

.book-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-top: 1px solid #f3f4f6;
    border-bottom: 1px solid #f3f4f6;
    margin-bottom: 18px;
}

.book-rating {
    display: flex;
    align-items: center;
    gap: 6px;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    width: 15px;
    height: 15px;
    color: #d1d5db;
    transition: all 0.3s ease;
}

.star.filled {
    color: #fbbf24;
    filter: drop-shadow(0 0 2px rgba(251, 191, 36, 0.5));
}

.rating-value {
    font-size: 0.875rem;
    font-weight: 700;
    color: #1f2937;
}

.book-copies {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8125rem;
    color: #6b7280;
    font-weight: 600;
}

.book-copies svg {
    width: 15px;
    height: 15px;
}

.book-actions {
    display: flex;
    gap: 10px;
}

.view-details-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.875rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.view-details-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.view-details-btn:hover::before {
    width: 300px;
    height: 300px;
}

.view-details-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.4);
}

.arrow-icon {
    width: 18px;
    height: 18px;
    transition: transform 0.3s ease;
}

.view-details-btn:hover .arrow-icon {
    transform: translateX(3px);
}

.add-cart-btn {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
}

.add-cart-btn svg {
    width: 22px;
    height: 22px;
}

.add-cart-btn.in-cart {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    cursor: not-allowed;
}

.add-cart-btn.unavailable {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    cursor: not-allowed;
}

/* ==================== EMPTY STATE ==================== */
.empty-state {
    text-align: center;
    padding: 60px 40px;
    border-radius: 20px;
    max-width: 500px;
    margin: 0 auto;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
}

.empty-icon svg {
    width: 50px;
    height: 50px;
    color: white;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}

.empty-subtitle {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 24px;
}

.reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.reset-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.4);
}

.reset-btn svg {
    width: 18px;
    height: 18px;
}

/* ==================== PAGINATION ==================== */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* ==================== UTILITY CLASSES ==================== */
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
}

/* ==================== ANIMATIONS ==================== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes bookReveal {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

.animate-fade-in-up-delay {
    animation: fadeInUp 0.8s ease-out 0.2s backwards;
}

.animate-scale-in {
    animation: scaleIn 1s ease-out 0.4s backwards;
}

.animate-book-reveal {
    animation: bookReveal 0.6s ease-out backwards;
    animation-delay: var(--delay, 0s);
}

/* ==================== RESPONSIVE DESIGN ==================== */
@media (max-width: 1024px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }
}

@media (max-width: 768px) {
    .page-hero {
        min-height: 40vh;
        padding: 60px 16px 40px;
    }

    .page-title {
        font-size: 2rem;
    }

    .search-filter-panel {
        padding: 20px;
    }

    .search-input-wrapper {
        flex-direction: column;
    }

    .search-btn-inline {
        width: 100%;
        justify-content: center;
        padding: 12px;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .results-info {
        flex-direction: column;
        gap: 12px;
    }

    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .books-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .page-title {
        font-size: 1.75rem;
    }
}

/* ==================== ACCESSIBILITY ==================== */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

.search-input:focus,
.filter-select:focus,
.search-btn-inline:focus,
.view-details-btn:focus,
.add-cart-btn:focus,
.reset-btn:focus {
    outline: 3px solid rgba(102, 126, 234, 0.5);
    outline-offset: 2px;
}
</style>

<!-- Flash Messages -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
        {{ session('error') }}
    </div>
@endif

@if(session('info'))
    <div class="fixed bottom-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
        {{ session('info') }}
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages
    setTimeout(() => {
        const messages = document.querySelectorAll('.fixed.bottom-4');
        messages.forEach(msg => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        });
    }, 3000);

    // Parallax effect
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero-bg-image');
                if (hero) {
                    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    // Book card animations
    const bookObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0) scale(1)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.book-card').forEach(card => {
        bookObserver.observe(card);
    });
});
</script>

@endsection
