@extends('layouts.app')
@section('title', 'Transactions')
@section('content')
<div style="margin-bottom:16px;">
    <a href="?filter=daily" class="btn {{ $filter==='daily' ? 'btn-primary' : '' }}" style="margin-right:8px;">Daily</a>
    <a href="?filter=weekly" class="btn {{ $filter==='weekly' ? 'btn-primary' : '' }}" style="margin-right:8px;">Weekly</a>
    <a href="?filter=monthly" class="btn {{ $filter==='monthly' ? 'btn-primary' : '' }}">Monthly</a>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="value">{{ $totalTransactions }}</div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value">₱{{ number_format($totalCoins, 2) }}</div><div class="label">Total Coins</div></div>
    <div class="stat-card"><div class="value">{{ $totalMinutes }}</div><div class="label">Total Minutes</div></div>
    <div class="stat-card"><div class="value">{{ $completedTransactions }}</div><div class="label">Completed</div></div>
</div>
<div class="card">
    <h3>Transactions</h3>
    <table>
        <thead><tr><th>ID</th><th>PC Unit</th><th>Coins</th><th>Minutes</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($transactions as $txn)
            <tr>
                <td>{{ $txn->transaction_id }}</td>
                <td>{{ $txn->pc_unit_id }}</td>
                <td>₱{{ number_format($txn->total_coins, 2) }}</td>
                <td>{{ $txn->total_minutes }}</td>
                <td>{{ $txn->transaction_date }}</td>
                <td><span class="badge {{ $txn->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($txn->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#666;">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
