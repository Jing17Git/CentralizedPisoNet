@extends('layouts.app')
@section('title', 'Transactions')
@section('content')

<div class="filter-bar">
    <span>Filter:</span>
    <a href="?filter=daily"   class="btn btn-outline {{ $filter==='daily'   ? 'active-filter' : '' }}" id="filter-daily">Daily</a>
    <a href="?filter=weekly"  class="btn btn-outline {{ $filter==='weekly'  ? 'active-filter' : '' }}" id="filter-weekly">Weekly</a>
    <a href="?filter=monthly" class="btn btn-outline {{ $filter==='monthly' ? 'active-filter' : '' }}" id="filter-monthly">Monthly</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value" id="totalTransactions">{{ $totalTransactions }}</div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalCoins">₱{{ number_format($totalCoins, 2) }}</div>
        <div class="stat-label">Total Coins</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalMinutes">{{ $totalMinutes }}</div>
        <div class="stat-label">Total Minutes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="completedTransactions">{{ $completedTransactions }}</div>
        <div class="stat-label">Completed</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>◈ Transactions <span class="update-ts" id="lastUpdate"></span></h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>PC Unit</th>
                <th>Coins</th>
                <th>Minutes</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="transactionsTable">
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
            <tr><td colspan="6" class="td-empty">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateTransactions() {
    const filter = new URLSearchParams(window.location.search).get('filter') || '';
    const url = '{{ route("transactions.index") }}' + (filter ? '?filter=' + filter : '');

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            ['totalTransactions','totalCoins','totalMinutes',
             'completedTransactions','transactionsTable'].forEach(id => {
                const src = doc.getElementById(id);
                const dst = document.getElementById(id);
                if (src && dst) dst.innerHTML = src.innerHTML;
            });
            const el = document.getElementById('lastUpdate');
            if (el) el.textContent = 'Updated ' + new Date().toLocaleTimeString();
        })
        .catch(err => console.error('Transactions update failed:', err));
}

updateInterval = setInterval(updateTransactions, 5000);
window.addEventListener('beforeunload', () => { if (updateInterval) clearInterval(updateInterval); });
</script>
@endsection
