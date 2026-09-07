@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="value">₱{{ number_format($todayEarnings, 2) }}</div>
        <div class="label">Today's Earnings</div>
    </div>
    <div class="stat-card">
        <div class="value">{{ $todayTransactions }}</div>
        <div class="label">Transactions Today</div>
    </div>
    <div class="stat-card">
        <div class="value">₱{{ number_format($avgTransaction, 2) }}</div>
        <div class="label">Avg Transaction</div>
    </div>
    <div class="stat-card">
        <div class="value">{{ count($machines) }}</div>
        <div class="label">Total Machines</div>
    </div>
</div>

<div class="card">
    <h3>Machine Status</h3>
    <table>
        <thead><tr><th>Machine</th><th>Status</th><th>Time</th></tr></thead>
        <tbody>
        @forelse($machines as $machine)
            <tr>
                <td>{{ $machine['id'] }}</td>
                <td>
                    @if($machine['status'] === 'online')
                        <span class="badge badge-success">Online</span>
                    @elseif($machine['status'] === 'busy')
                        <span class="badge badge-warning">Busy</span>
                    @else
                        <span class="badge badge-danger">Offline</span>
                    @endif
                </td>
                <td>{{ $machine['time'] }}</td>
            </tr>
        @empty
            <tr><td colspan="3" style="text-align:center;color:#666;">No machines registered yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Recent Transactions</h3>
    <table>
        <thead><tr><th>PC Unit</th><th>Coins</th><th>Minutes</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($recentTransactions as $txn)
            <tr>
                <td>{{ $txn->pc_unit_id }}</td>
                <td>₱{{ number_format($txn->total_coins, 2) }}</td>
                <td>{{ $txn->total_minutes }} min</td>
                <td>{{ $txn->transaction_date }}</td>
                <td><span class="badge {{ $txn->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($txn->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;color:#666;">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
