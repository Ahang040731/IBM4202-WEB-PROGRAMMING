<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\User;
use App\Models\CreditTransaction;
use App\Models\BorrowHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FinesController extends Controller
{
    // Display fines for the current user
    public function index()
    {
        $account = auth()->user();  // Account model (from accounts table)

        if (!$account || !$account->user) {
            abort(403, 'No user profile linked to this account.');
        }

        $user = $account->user;     // related User profile
        $userId = $user->id;        // matches fines.user_id & borrow_histories.user_id

        /*
        |--------------------------------------------------------------------------
        | 1) Auto-generate fines for overdue borrows with no fine yet
        |--------------------------------------------------------------------------
        |
        | We look for BorrowHistory rows:
        |   - for this user
        |   - not returned
        |   - due date is in the past
        |   - and they don't already have a Fine record
        */

        $overdueBorrows = BorrowHistory::where('user_id', $userId)
            ->whereNull('returned_at')
            ->where('due_at', '<', now())
            ->whereDoesntHave('fine') // requires fine() relation on BorrowHistory
            ->get();

        foreach ($overdueBorrows as $borrow) {
            // simple fine calculation: X RM per late day
            $lateDays = now()->diffInDays($borrow->due_at);
            $ratePerDay = config('library.fine_per_day', 1.00); // you can change this
            $amount = max(0, $lateDays * $ratePerDay);

            Fine::create([
                'user_id'           => $userId,
                'borrow_history_id' => $borrow->id,
                'amount'            => $amount,
                'status'            => 'unpaid',
                'reason'            => 'Overdue book',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2) Load current (unpaid) fines
        |--------------------------------------------------------------------------
        */

        $current = Fine::with(['borrowHistory.book'])
            ->where('user_id', $userId)
            ->where('status', 'unpaid')
            ->orderBy('created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 3) Load previous (paid / waived / reversed) fines
        |--------------------------------------------------------------------------
        */

        $previous = Fine::with(['borrowHistory.book'])
            ->where('user_id', $userId)
            ->whereIn('status', ['paid', 'waived', 'reversed'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.fines.index', compact('current', 'previous'));
    }

    // Pay a fine
    public function pay(Fine $fine, Request $request): RedirectResponse
    {
        $account = auth()->user();

        if (!$account || !$account->user) {
            abort(403, 'No user profile linked to this account.');
        }

        $user = $account->user;  // User profile (has credit column)

        // Ensure the fine belongs to this user
        if ($fine->user_id !== $user->id) {
            abort(403);
        }

        // Only unpaid fines can be paid
        if ($fine->status !== 'unpaid') {
            return back()->with('error', 'This fine has already been processed.');
        }

        // Get payment method (default credit)
        $method = $request->input('method', 'credit');

        // Handle credit payment
        if ($method === 'credit') {
            if ($user->credit < $fine->amount) {
                return back()->with('error', 'Insufficient credit to pay this fine.');
            }

            // Deduct user credit
            $user->decrement('credit', $fine->amount);

            // Record credit transaction
            CreditTransaction::create([
                'user_id'    => $user->id,
                'delta'      => -$fine->amount,
                'reason'     => 'fine',
                'method'     => 'system',
                'reference'  => 'FINE#'.$fine->id,
            ]);
        }

        // Update fine record
        $fine->update([
            'status'          => 'paid',
            'paid_at'         => now(),
            'method'          => $method,
            'transaction_ref' => 'TXN'.time(),
        ]);

        return back()->with('success', 'Fine has been paid successfully.');
    }
}
