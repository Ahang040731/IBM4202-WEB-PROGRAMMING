<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\BorrowHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\CreditTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinesController extends Controller
{
    public function index()
    {
        $account = auth()->user();  // Account model (accounts table)

        if (!$account || !$account->user) {
            abort(403, 'No user profile linked to this account.');
        }

        $user   = $account->user;     // user profile (users table)
        $userId = $user->id;          // matches fines.user_id & borrow_history.user_id

        /*
        |------------------------------------------------------------------
        | 1) Auto-generate fines for overdue borrows with no fine yet
        |    (book NOT returned yet → use today vs due_at)
        |------------------------------------------------------------------
        */

        $overdueBorrows = BorrowHistory::where('user_id', $userId)
            ->whereNull('returned_at')
            ->whereNotNull('due_at')
            ->where('due_at', '<', Carbon::now())
            ->get();


        foreach ($overdueBorrows as $borrow) {

            $daysLate = $borrow->late_days;

            if ($daysLate <= 0) {
                continue;
            }

            $baseFine   = 5.00;
            $latePerDay = 2.00;
            $fineAmount = $baseFine + ($daysLate * $latePerDay);

            // Lost books get RM100 extra
            $isLostBorrow = ($borrow->status === 'lost');
            if ($isLostBorrow) {
                $fineAmount += 100.00;
            }

            $fineAmount = round($fineAmount, 2);
            $reason     = $isLostBorrow ? 'lost' : 'late';

            // Find existing fine record
            $fine = Fine::where('borrowing_id', $borrow->id)->first();

            // If fine exists and is NOT unpaid → don't touch
            if ($fine && !in_array($fine->status, ['unpaid'])) {
                continue;
            }

            // Update or create (will also update amount if already unpaid)
            Fine::updateOrCreate(
                [
                    'borrowing_id' => $borrow->id,
                    'user_id'      => $userId,
                ],
                [
                    'reason' => $reason,
                    'amount' => $fineAmount,
                    'status' => 'unpaid',
                ]
            );
        }



        /*
        |------------------------------------------------------------------
        | 2) Load unpaid + pending (current)
        |------------------------------------------------------------------
        */

        $current = Fine::with(['borrowHistory.book'])
            ->where('user_id', $userId)
            ->whereIn('status', ['unpaid', 'pending'])
            ->orderBy('created_at')
            ->get();

        /*
        |------------------------------------------------------------------
        | 3) Load paid/waived/reversed (previous)
        |------------------------------------------------------------------
        */

        $previous = Fine::with(['borrowHistory.book'])
            ->where('user_id', $userId)
            ->whereIn('status', ['paid', 'waived', 'reversed'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.fines.index', compact('current', 'previous'));
    }

    public function pay(Fine $fine, Request $request): RedirectResponse
    {
        $account = auth()->user();

        if (!$account || !$account->user) {
            abort(403, 'No user profile linked to this account.');
        }

        $user = $account->user;

        // Make sure the fine belongs to this user
        if ($fine->user_id !== $user->id) {
            abort(403);
        }

        $borrowing = $fine->borrowHistory;

        // Still block if book not returned and not marked as lost
        if (
            $borrowing &&
            $borrowing->returned_at === null &&
            $borrowing->status !== 'lost'
        ) {
            return back()->with('error', 'You must return the book before paying the fine.');
        }

        //  Only UNPAID fines can be paid
        if ($fine->status !== 'unpaid') {
            return back()->with('error', 'This fine is already processed or completed.');
        }

        // 🔹 Force credit payment
        $amount = $fine->amount;

        // Check credit balance
        if ($user->credit < $amount) {
            return back()->with('error', 'Not enough credit to pay this fine.');
        }

        DB::transaction(function () use ($user, $fine, $amount) {

            // 1) Deduct from user credit
            $user->decrement('credit', $amount);

            // 2) Log credit transaction
            CreditTransaction::create([
                'user_id'   => $user->id,
                'delta'     => -$amount, // negative = deduction
                'reason'    => $fine->reason === 'lost' ? 'lost' : 'fine',
                'method'    => 'credit',
                'reference' => 'FINE-' . $fine->id . '-' . time(),
            ]);

            // 3) Mark fine as PAID immediately
            $fine->update([
                'status'          => 'paid',
                'method'          => 'credit',
                'transaction_ref' => 'FINE-' . $fine->id . '-' . time(),
                'paid_at'         => now(),
            ]);
        });

        return back()->with('success', 'Fine has been paid using your credit balance.');
    }

}
