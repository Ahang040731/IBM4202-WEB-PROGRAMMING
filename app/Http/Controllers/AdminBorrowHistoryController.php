<?php

namespace App\Http\Controllers;

use App\Models\BorrowHistory;
use Illuminate\Http\Request;

class AdminBorrowHistoryController extends Controller
{
    // 显示所有借书记录
    public function index()
    {
        // eager load 用户、书籍、书籍副本
        $borrows = BorrowHistory::with(['user', 'book', 'copy'])
                    ->orderBy('borrowed_at', 'desc')
                    ->paginate(10);

        return view('admin.borrowhistorymanagement.index', compact('borrows'));
    }

    // 标记书籍为已归还
    public function markReturned($id)
    {
        $borrow = BorrowHistory::findOrFail($id);
        $borrow->status = 'returned';
        $borrow->returned_at = now();
        $borrow->save();

        // 书籍副本状态更新
        $borrow->copy->update(['status' => 'available']);

        return redirect()->route('admin.borrowhistorymanagement.index')
                         ->with('success', 'Borrow record updated as returned.');
    }
}
