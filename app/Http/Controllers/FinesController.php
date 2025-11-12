<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinesController extends Controller
{
    public function index()
    {
        // 假数据示例
        $fines = [
            ['id' => 1, 'type' => '逾期归还', 'amount' => 5.00, 'status' => '未支付'],
            ['id' => 2, 'type' => '书籍损坏', 'amount' => 12.50, 'status' => '已支付'],
        ];

        
        return view('client.fines.index', compact('fines'));
    }
}
?>