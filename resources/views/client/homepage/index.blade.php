@extends('layouts.app')

@section('title', 'Discover - Library Dashboard')

@section('content')
<div class="space-y-8">
    
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Discover</h1>
            <p class="text-gray-600 mt-1">Explore our curated collection of books</p>
        </div>
        
        {{-- Filters --}}
        <div class="flex items-center space-x-3">
            {{-- Category Filter --}}
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option>All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            
            {{-- Search Button --}}
            <button class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
                Search
            </button>
        </div>
    </div>
    
    {{-- Book Recommendations Section --}}
    <section class="fade-in" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Book Recommendation</h2>
            <a href="{{ route('books.recommendations') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center space-x-1">
                <span>View All</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        
        {{-- Scrollable Book Grid --}}
        <div class="overflow-x-auto pb-4 -mx-2 px-2">
            <div class="flex space-x-6 min-w-max">
                @foreach($recommendedBooks as $index => $book)
                    <div class="book-card group cursor-pointer" style="animation-delay: {{ 0.1 * ($index + 1) }}s">
                        <div class="relative">
                            {{-- Book Cover --}}
                            <div class="w-48 h-72 rounded-xl overflow-hidden shadow-lg bg-gray-100 relative">
                                @if($book->photo)
                                    <img src="{{ asset('storage/' . $book->photo) }}" 
                                         alt="{{ $book->book_name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <div class="transform scale-75 group-hover:scale-100 transition-transform">
                                        <button class="px-4 py-2 bg-white text-gray-900 rounded-lg font-medium hover:bg-gray-100">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- Rating Badge --}}
                                @if($book->rating)
                                    <div class="absolute top-3 right-3 bg-yellow-400 text-gray-900 px-2 py-1 rounded-lg text-xs font-bold flex items-center space-x-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span>{{ number_format($book->rating, 1) }}</span>
                                    </div>
                                @endif
                                
                                {{-- Favorite Button --}}
                                <button class="absolute top-3 left-3 w-8 h-8 bg-white bg-opacity-90 rounded-full flex items-center justify-center hover:bg-opacity-100 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-gray-700 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                            
                            {{-- Book Info --}}
                            <div class="mt-3 space-y-1">
                                <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 group-hover:text-blue-600">
                                    {{ $book->book_name }}
                                </h3>
                                <p class="text-xs text-gray-600">
                                    {{ $book->author }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $book->published_year }}</span>
                                    @if($book->available_copies > 0)
                                        <span class="text-xs text-green-600 font-medium">{{ $book->available_copies }} available</span>
                                    @else
                                        <span class="text-xs text-red-600 font-medium">Not available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    {{-- Book Categories Section --}}
    <section class="fade-in" style="animation-delay: 0.4s">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Book Categories</h2>
            <button class="w-8 h-8 border-2 border-gray-300 rounded-full flex items-center justify-center hover:border-blue-600 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </button>
        </div>
        
        {{-- Category Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            
            @foreach($bookCategories as $index => $categoryData)
                <a href="{{ route('books.category', $categoryData['slug']) }}" 
                   class="hover-scale rounded-2xl overflow-hidden shadow-md hover:shadow-xl cursor-pointer"
                   style="animation-delay: {{ 0.1 * ($index + 1) }}s; animation-name: fadeIn;">
                    
                    {{-- Category Card with gradient background --}}
                    <div class="relative h-48 bg-gradient-to-br {{ $categoryData['gradient'] }} p-6 flex flex-col justify-between">
                        
                        {{-- Category Icon/Image --}}
                        @if($categoryData['image'])
                            <div class="absolute inset-0 opacity-20">
                                <img src="{{ asset('storage/' . $categoryData['image']) }}" 
                                     alt="{{ $categoryData['name'] }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        {{-- Category Content --}}
                        <div class="relative z-10">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-lg mb-1">{{ $categoryData['name'] }}</h3>
                                    <p class="text-white text-opacity-90 text-sm">{{ $categoryData['count'] }} books</p>
                                </div>
                                
                                {{-- Category Icon --}}
                                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    {!! $categoryData['icon'] !!}
                                </div>
                            </div>
                        </div>
                        
                        {{-- Bottom tag or label --}}
                        <div class="relative z-10">
                            <span class="inline-block px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-white text-xs font-medium">
                                {{ $categoryData['tag'] ?? 'Popular' }}
                            </span>
                        </div>
                        
                    </div>
                </a>
            @endforeach
            
        </div>
    </section>
    
    {{-- Recently Added Books --}}
    <section class="fade-in" style="animation-delay: 0.6s">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Recently Added</h2>
            <a href="{{ route('books.recent') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center space-x-1">
                <span>View All</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        
        {{-- Recently Added Books Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @foreach($recentBooks as $index => $book)
                <div class="book-card group cursor-pointer" style="animation-delay: {{ 0.05 * ($index + 1) }}s">
                    <div class="relative">
                        {{-- Book Cover --}}
                        <div class="w-full aspect-[2/3] rounded-lg overflow-hidden shadow-md bg-gray-100 relative">
                            @if($book->photo)
                                <img src="{{ asset('storage/' . $book->photo) }}" 
                                     alt="{{ $book->book_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-pink-600 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            {{-- New Badge --}}
                            <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                                NEW
                            </div>
                        </div>
                        
                        {{-- Book Info --}}
                        <div class="mt-2 space-y-1">
                            <h3 class="font-medium text-gray-900 text-xs line-clamp-2 group-hover:text-blue-600">
                                {{ $book->book_name }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ Str::limit($book->author, 20) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    
    {{-- Popular This Week --}}
    <section class="fade-in" style="animation-delay: 0.8s">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Popular This Week</h2>
                <p class="text-gray-600 text-sm mt-1">Most borrowed books this week</p>
            </div>
        </div>
        
        {{-- Popular Books List --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($popularBooks as $index => $book)
                <div class="flex space-x-4 p-4 bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all cursor-pointer"
                     style="animation-delay: {{ 0.1 * ($index + 1) }}s; animation-name: fadeIn;">
                    
                    {{-- Rank Number --}}
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ $index + 1 }}</span>
                    </div>
                    
                    {{-- Book Cover Thumbnail --}}
                    <div class="flex-shrink-0 w-16 h-24 rounded-lg overflow-hidden bg-gray-100">
                        @if($book->photo)
                            <img src="{{ asset('storage/' . $book->photo) }}" 
                                 alt="{{ $book->book_name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600"></div>
                        @endif
                    </div>
                    
                    {{-- Book Details --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $book->book_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $book->author }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($book->rating, 1) }}</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $book->category }}</span>
                        </div>
                    </div>
                    
                    {{-- Action Button --}}
                    <div class="flex-shrink-0 flex items-center">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            Borrow
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    
</div>
@endsection

@push('scripts')
<script>
    // Add smooth scroll behavior for horizontal book scrolling
    document.querySelectorAll('.overflow-x-auto').forEach(container => {
        let isDown = false;
        let startX;
        let scrollLeft;
        
        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.style.cursor = 'grabbing';
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });
        
        container.addEventListener('mouseleave', () => {
            isDown = false;
            container.style.cursor = 'grab';
        });
        
        container.addEventListener('mouseup', () => {
            isDown = false;
            container.style.cursor = 'grab';
        });
        
        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });
    });
    
    // Add click handlers for book cards
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                // Navigate to book details page
                console.log('Book clicked');
            }
        });
    });
</script>
@endpush