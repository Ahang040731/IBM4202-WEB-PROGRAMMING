@extends('layouts.app')

@section('title', 'Fines')

@section('content')

<!-- Your Content -->
<div class="container mt-4">
    <h1>Fines</h1>
    <p>这里显示您的罚款信息。</p>

    @if(isset($fines) && count($fines) > 0)
        <table class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>罚款类型</th>
                    <th>金额 (RM)</th>
                    <th>状态</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fines as $index => $fine)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $fine['type'] ?? 'N/A' }}</td>
                        <td>{{ number_format($fine['amount'] ?? 0, 2) }}</td>
                        <td>{{ $fine['status'] ?? '未知' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>总罚款: RM {{ number_format(array_sum(array_column($fines, 'amount')), 2) }}</p>
    @else
        <p>您目前没有罚款记录。</p>
    @endif
</div>
@endsection