@extends('layouts.app')

@section('title', 'Favorites')

@section('content')
  <div class="bh-container">
  <h1 class="bh-h1">Your Favorite Books</h1>

  @if(($books ?? collect())->isEmpty())
    <div class="bg-white rounded-xl border p-8 text-center text-slate-600">
      No favorites yet. Go add some ❤️
    </div>
  @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
      @foreach($books as $book)
        <div class="relative bg-white rounded-2xl border shadow-sm hover:shadow-md transition overflow-hidden">
          {{-- Unfavorite (star) --}}
          <form method="POST" action="{{ route('favorites.toggle', $book->id) }}" class="absolute top-2 right-2 z-10">
            @csrf
            <button type="submit" class="favorite-btn {{ $book->is_favorited ? 'active' : '' }}">
                <svg fill="{{ $book->is_favorited ? 'currentColor' : 'none' }}"
                    stroke="currentColor"
                    class="w-5 h-5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
          </form>

          {{-- Image --}}
          <div class="relative bg-slate-100 aspect-[3/4]">
            @php
              $src = null;
              if (!empty($book->photo)) {
                $src = Str::startsWith($book->photo, ['http://','https://'])
                        ? $book->photo
                        : asset('storage/'.$book->photo);
              }
            @endphp
            @if($src)
              <img src="{{ $src }}" alt="{{ $book->book_name }}" class="absolute inset-0 w-full h-full object-cover">
            @else
              <div class="absolute inset-0 flex items-center justify-center text-slate-400">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
              </div>
            @endif
          </div>

          {{-- Title under image --}}
          <div class="p-3">
            <h3 class="bh-title" style="text-align: center;">
              {{ $book->book_name }}
            </h3>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Pagination (if you paginate in controller) --}}
    @if(method_exists($books, 'links'))
      <div class="mt-6">
        {{ $books->links() }}
      </div>
    @endif
  @endif
</div>
@endsection

<style>
  .favorite-form {
    position: absolute;
    top: 12px;
    left: 12px;
}

.favorite-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.favorite-btn svg {
    width: 20px;
    height: 20px;
    color: #ef4444; /* red */
}

.favorite-btn:hover {
    transform: scale(1.1);
}

/* Active state (favorited) */
.favorite-btn.active {
    background: white; /* red circle */
}

.favorite-btn.active svg {
    color: #ef4444; /* white heart (or change to red if you want) */
}

</style>