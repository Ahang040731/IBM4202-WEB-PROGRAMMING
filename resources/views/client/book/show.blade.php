@extends('layouts.app')

@section('title', $book->book_name)

@section('content')

<div class="book-detail-container">
    <!-- Hero Section with Book Preview -->
    <div class="detail-hero">
        <div class="hero-background">
            <div class="hero-bg-image" style="background-image: url('{{ $book->photo ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1920&q=80' }}')"></div>
            <div class="hero-overlay"></div>
        </div>

        <!-- Back Button -->
        <div class="back-button-wrapper">
            <a href="{{ route('client.books.index') }}" class="back-btn glass-effect">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Books</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="detail-content">
        <div class="content-wrapper glass-effect">
            <div class="content-grid">
                <!-- Book Cover Column -->
                <div class="cover-column">
                    <div class="cover-card glass-effect">
                        @if($book->photo)
                            <img src="{{ $book->photo }}" alt="{{ $book->book_name }}" class="cover-image">
                        @else
                            <div class="cover-placeholder">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>No Cover Available</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="detail-status {{ $book->available_copies > 0 ? 'available' : 'unavailable' }}">
                            <span class="status-dot"></span>
                            <span>{{ $book->available_copies > 0 ? 'Available' : 'Out of Stock' }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @if(isset($cartItem) && $cartItem)
                            <div class="in-cart-notice">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Already in cart ({{ $cartItem->quantity }} {{ $cartItem->quantity > 1 ? 'copies' : 'copy' }})</span>
                            </div>
                            <a href="{{ route('client.cart.index') }}" class="action-btn primary">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Go to Cart</span>
                            </a>
                        @elseif($book->available_copies > 0)
                            <form action="{{ route('client.cart.store', $book) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="action-btn success">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span>Add to Borrow Cart</span>
                                </button>
                            </form>
                        @else
                            <button class="action-btn disabled" disabled>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Currently Unavailable</span>
                            </button>
                        @endif

                        <!-- Favorite Button -->
                        <form action="{{ route('favourites.toggle', $book) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="action-btn {{ isset($isFavorite) && $isFavorite ? 'favorite-active' : 'favorite' }}">
                                <svg fill="{{ isset($isFavorite) && $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ isset($isFavorite) && $isFavorite ? 'Remove from Favorites' : 'Add to Favorites' }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Book Details Column -->
                <div class="details-column">
                    <!-- Category Badge -->
                    @if($book->category)
                    <div class="category-badge-lg">
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
                    @endif

                    <!-- Title and Author -->
                    <h1 class="book-detail-title">{{ $book->book_name }}</h1>
                    <p class="book-detail-author">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        by {{ $book->author }}
                    </p>

                    <!-- Rating and Year -->
                    <div class="book-detail-meta">
                        <div class="meta-rating">
                            <div class="stars-large">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="star {{ $i <= floor($book->rating) ? 'filled' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="rating-text">{{ number_format($book->rating, 1) }} / 5.0</span>
                        </div>
                        <div class="meta-year">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $book->published_year }}</span>
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div class="availability-card glass-effect">
                        <div class="availability-header">
                            <h3>Availability Status</h3>
                            @if($book->available_copies > 0)
                                <svg class="status-icon success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="status-icon danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="availability-info">
                            <span class="copies-available {{ $book->available_copies > 0 ? 'success' : 'danger' }}">
                                {{ $book->available_copies }}
                            </span>
                            <span class="copies-text">of {{ $book->total_copies }} copies available</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description-section">
                        <h3 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Description
                        </h3>
                        <p class="description-text">
                            {{ $book->description ?? 'No description available for this book.' }}
                        </p>
                    </div>

                    <!-- Book Information -->
                    <div class="info-section">
                        <h3 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Book Information
                        </h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Author</span>
                                <span class="info-value">{{ $book->author }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Published Year</span>
                                <span class="info-value">{{ $book->published_year }}</span>
                            </div>
                            @if($book->category)
                            <div class="info-item">
                                <span class="info-label">Category</span>
                                <span class="info-value">{{ $book->category }}</span>
                            </div>
                            @endif
                            <div class="info-item">
                                <span class="info-label">Total Copies</span>
                                <span class="info-value">{{ $book->total_copies }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Rating</span>
                                <span class="info-value">{{ number_format($book->rating, 1) }} / 5.0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Available Copies List -->
                    @if($availableCopies->count() > 0)
                    <div class="copies-section">
                        <h3 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Available Copies
                            @if($totalAvailableCopies > 10)
                                <span class="copies-badge">{{ $totalAvailableCopies }} total</span>
                            @endif
                        </h3>
                        <div class="copies-grid">
                            @foreach($availableCopies as $copy)
                                <div class="copy-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="copy-number">{{ $copy->copy_number }}</span>
                                    <span class="copy-status">Available</span>
                                </div>
                            @endforeach
                        </div>
                        @if($totalAvailableCopies > 10)
                            <p class="copies-notice">
                                + {{ $totalAvailableCopies - 10 }} more copies available
                            </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ==================== BASE STYLES ==================== */
.book-detail-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

/* ==================== DETAIL HERO ==================== */
.detail-hero {
    position: relative;
    min-height: 35vh;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 80px 20px 40px;
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
    filter: blur(15px);
    transform: scale(1.1);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(102, 126, 234, 0.95) 0%,
        rgba(118, 75, 162, 0.92) 50%,
        rgba(102, 126, 234, 0.95) 100%);
}

.back-button-wrapper {
    position: relative;
    z-index: 2;
    max-width: 1400px;
    width: 100%;
    margin: 0 auto;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.95);
    color: #667eea;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    background: white;
}

.back-btn svg {
    width: 20px;
    height: 20px;
}

/* ==================== DETAIL CONTENT ==================== */
.detail-content {
    max-width: 1400px;
    margin: -20px auto 60px;
    padding: 0 20px;
    position: relative;
    z-index: 3;
}

.content-wrapper {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.content-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 48px;
}

/* ==================== COVER COLUMN ==================== */
.cover-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cover-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.cover-image {
    width: 100%;
    height: auto;
    aspect-ratio: 2/3;
    object-fit: cover;
    display: block;
}

.cover-placeholder {
    width: 100%;
    aspect-ratio: 2/3;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
}

.cover-placeholder svg {
    width: 80px;
    height: 80px;
    color: rgba(255, 255, 255, 0.5);
}

.cover-placeholder span {
    color: white;
    font-weight: 600;
    font-size: 1rem;
    text-align: center;
}

.detail-status {
    position: absolute;
    top: 16px;
    right: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.8; }
}

.detail-status.available {
    background: rgba(16, 185, 129, 0.95);
    color: white;
}

.detail-status.available .status-dot {
    background: white;
}

.detail-status.unavailable {
    background: rgba(239, 68, 68, 0.95);
    color: white;
}

.detail-status.unavailable .status-dot {
    background: white;
}

/* ==================== ACTION BUTTONS ==================== */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.w-full {
    width: 100%;
}

.in-cart-notice {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 12px;
    color: #374151;
    font-size: 0.875rem;
    font-weight: 600;
}

.in-cart-notice svg {
    width: 20px;
    height: 20px;
    color: #10b981;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 24px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 100%;
}

.action-btn::before {
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

.action-btn:hover::before {
    width: 300px;
    height: 300px;
}

.action-btn svg {
    width: 22px;
    height: 22px;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.action-btn.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.action-btn.success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.action-btn.favorite {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    color: #6b7280;
}

.action-btn.favorite:hover {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(239, 68, 68, 0.2);
}

.action-btn.favorite-active {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
}

.action-btn.favorite-active:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
}

.action-btn.disabled {
    background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%);
    color: white;
    cursor: not-allowed;
}

/* ==================== DETAILS COLUMN ==================== */
.details-column {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.category-badge-lg {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
    color: #7c3aed;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 700;
    border: 1px solid rgba(124, 58, 237, 0.2);
    width: fit-content;
}

.category-icon {
    font-size: 1.125rem;
}

.book-detail-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: #1f2937;
    line-height: 1.2;
    margin: 0;
}

.book-detail-author {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.25rem;
    color: #6b7280;
    font-weight: 600;
}

.book-detail-author svg {
    width: 22px;
    height: 22px;
}

.book-detail-meta {
    display: flex;
    align-items: center;
    gap: 32px;
    padding: 20px 0;
    border-top: 2px solid #f3f4f6;
    border-bottom: 2px solid #f3f4f6;
}

.meta-rating {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stars-large {
    display: flex;
    gap: 4px;
}

.stars-large .star {
    width: 24px;
    height: 24px;
    color: #d1d5db;
}

.stars-large .star.filled {
    color: #fbbf24;
    filter: drop-shadow(0 0 3px rgba(251, 191, 36, 0.5));
}

.rating-text {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}

.meta-year {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-weight: 600;
}

.meta-year svg {
    width: 20px;
    height: 20px;
}

/* ==================== AVAILABILITY CARD ==================== */
.availability-card {
    padding: 24px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #e5e7eb;
}

.availability-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.availability-header h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #374151;
    margin: 0;
}

.status-icon {
    width: 36px;
    height: 36px;
}

.status-icon.success {
    color: #10b981;
}

.status-icon.danger {
    color: #ef4444;
}

.availability-info {
    display: flex;
    align-items: baseline;
    gap: 12px;
}

.copies-available {
    font-size: 3rem;
    font-weight: 900;
    line-height: 1;
}

.copies-available.success {
    color: #10b981;
}

.copies-available.danger {
    color: #ef4444;
}

.copies-text {
    font-size: 1rem;
    color: #6b7280;
    font-weight: 600;
}

/* ==================== SECTIONS ==================== */
.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.25rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 16px;
}

.section-title svg {
    width: 24px;
    height: 24px;
    color: #667eea;
}

.description-section {
    padding: 24px;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 16px;
}

.description-text {
    font-size: 1rem;
    line-height: 1.8;
    color: #374151;
}

/* ==================== INFO GRID ==================== */
.info-section {
    padding: 24px;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 16px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
}

/* ==================== COPIES SECTION ==================== */
.copies-section {
    padding: 24px;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border-radius: 16px;
}

.copies-badge {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    background: white;
    padding: 4px 12px;
    border-radius: 50px;
    margin-left: auto;
}

.copies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
}

.copy-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px;
    background: white;
    border-radius: 12px;
    border: 2px solid #d1fae5;
}

.copy-item svg {
    width: 20px;
    height: 20px;
    color: #10b981;
    flex-shrink: 0;
}

.copy-number {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.copy-status {
    font-size: 0.75rem;
    font-weight: 700;
    color: #10b981;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: auto;
}

.copies-notice {
    margin-top: 16px;
    text-align: center;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
}

/* ==================== UTILITY CLASSES ==================== */
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
}

/* ==================== RESPONSIVE DESIGN ==================== */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 350px 1fr;
        gap: 32px;
    }
}

@media (max-width: 768px) {
    .detail-hero {
        min-height: 25vh;
        padding: 60px 16px 30px;
    }

    .content-wrapper {
        padding: 28px;
    }

    .content-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .cover-column {
        max-width: 400px;
        margin: 0 auto;
        width: 100%;
    }

    .book-detail-title {
        font-size: 2rem;
    }

    .book-detail-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .copies-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .content-wrapper {
        padding: 20px;
    }

    .book-detail-title {
        font-size: 1.75rem;
    }

    .action-btn {
        padding: 14px 20px;
        font-size: 0.875rem;
    }

    .section-title {
        font-size: 1.125rem;
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

.back-btn:focus,
.action-btn:focus {
    outline: 3px solid rgba(102, 126, 234, 0.5);
    outline-offset: 2px;
}
</style>

<!-- Flash Messages -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
@endif

@if(session('info'))
    <div class="fixed bottom-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
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
});
</script>

@endsection
