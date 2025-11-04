<?php

namespace App\Http\Controllers;

use App\Models\BorrowHistory;

class BorrowHistoryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Current borrows (not returned)
        $current = BorrowHistory::with(['book','copy'])
            ->where('user_id', $userId)
            ->whereNull('returned_at')
            ->orderBy('due_at') // soonest due first
            ->get();

        // Previous borrows (returned)
        $previous = BorrowHistory::with(['book','copy'])
            ->where('user_id', $userId)
            ->whereNotNull('returned_at')
            ->orderByDesc('returned_at')
            ->paginate(10);

        return view('client.borrowhistory.index', compact('current','previous'));
    }
}
