@extends('layouts.app')

@section('title', 'Borrow History')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Borrow & Return History</h1>

{{-- ===== Current Borrow (not returned) ===== --}}
<section class="mb-10">
  <h2 class="text-lg font-semibold mb-3">Current Borrow</h2>

  @if($current->isEmpty())
    <div class="rounded border p-4 text-gray-600">No current borrows.</div>
  @else
    <ul class="space-y-3">
      @foreach($current as $row)
        @php
          $book = $row->book ?? null;
          $copy = $row->copy ?? null;
          $isOverdue = is_null($row->returned_at) && \Illuminate\Support\Carbon::parse($row->due_at)->lt(now());
        @endphp

        <li class="border rounded p-4 flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <div class="font-medium">
              {{ $book->book_name ?? 'Unknown Book' }}
              <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                {{ $isOverdue ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $isOverdue ? 'Overdue' : 'Active' }}
              </span>
            </div>
            <div class="text-sm text-gray-500">
              Copy: {{ $copy->barcode ?? $row->copy_id }}
            </div>
          </div>

          <div class="grid gap-2 text-sm text-gray-700 md:grid-cols-3">
            <div>Borrowed: <span class="font-medium">{{ $row->borrowed_at }}</span></div>
            <div>Due: <span class="font-medium">{{ $row->due_at }}</span></div>
            <div>Extensions: <span class="font-medium">{{ $row->extension_count }}</span></div>
          </div>

          {{-- Actions: wire later to your routes if needed --}}
          {{-- <form method="post" action="{{ route('client.borrowed.extend', $row->id) }}">@csrf <button class="px-3 py-1.5 rounded border text-sm">Request Extension</button></form> --}}
        </li>
      @endforeach
    </ul>
  @endif
</section>

{{-- ===== Previous Borrow (returned) ===== --}}
<section>
  <h2 class="text-lg font-semibold mb-3">Previous Borrow</h2>

  @if($previous->isEmpty())
    <div class="rounded border p-4 text-gray-600">No previous borrows.</div>
  @else
    <ul class="space-y-3 mb-4">
      @foreach($previous as $row)
        @php
          $book = $row->book ?? null;
          $copy = $row->copy ?? null;
          $due = \Illuminate\Support\Carbon::parse($row->due_at);
          $ret = $row->returned_at ? \Illuminate\Support\Carbon::parse($row->returned_at) : null;
          $lateDays = ($ret && $ret->gt($due)) ? $ret->diffInDays($due) :
                      ((!$ret && $due->lt(now())) ? now()->diffInDays($due) : 0);
        @endphp

        <li class="border rounded p-4 flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <div class="font-medium">
              {{ $book->book_name ?? 'Unknown Book' }}
              <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">Returned</span>
            </div>
            <div class="text-sm text-gray-500">
              Copy: {{ $copy->barcode ?? $row->copy_id }}
            </div>
          </div>

          <div class="grid gap-2 text-sm text-gray-700 md:grid-cols-4">
            <div>Borrowed: <span class="font-medium">{{ $row->borrowed_at }}</span></div>
            <div>Due: <span class="font-medium">{{ $row->due_at }}</span></div>
            <div>Returned: <span class="font-medium">{{ $row->returned_at ?? '—' }}</span></div>
            <div>Late Days: <span class="font-medium">{{ $lateDays }}</span></div>
          </div>

          {{-- Actions: wire later if you add a re-borrow route --}}
          {{-- <form method="post" action="{{ route('client.returns.reborrow', $row->id) }}">@csrf <button class="px-3 py-1.5 rounded border text-sm">Borrow Again</button></form> --}}
        </li>
      @endforeach
    </ul>

    {{-- Pagination for previous borrows --}}
    {{ $previous->links() }}
  @endif
</section>
@endsection