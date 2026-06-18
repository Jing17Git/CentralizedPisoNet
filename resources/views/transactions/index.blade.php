@extends('layouts.app')
@section('title', 'Transactions')
@section('content')
<div style="margin-bottom:16px;">
    <a href="?filter=daily" class="btn {{ $filter==='daily' ? 'btn-primary' : '' }}" style="margin-right:8px;">Daily</a>
    <a href="?filter=weekly" class="btn {{ $filter==='weekly' ? 'btn-primary' : '' }}" style="margin-right:8px;">Weekly</a>
    <a href="?filter=monthly" class="btn {{ $filter==='monthly' ? 'btn-primary' : '' }}">Monthly</a>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="value" id="totalTransactions">{{ $totalTransactions }}</div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value" id="totalCoins">₱{{ number_format($totalCoins, 2) }}</div><div class="label">Total Coins</div></div>
    <div class="stat-card"><div class="value" id="totalMinutes">{{ $totalMinutes }}</div><div class="label">Total Minutes</div></div>
    <div class="stat-card"><div class="value" id="completedTransactions">{{ $completedTransactions }}</div><div class="label">Completed</div></div>
</div>
<div class="card">
    <h3>Transactions <span style="font-size:12px;color:#666;" id="lastUpdate"></span></h3>
    <table>
        <thead><tr><th>ID</th><th>PC Unit</th><th>Coins</th><th>Minutes</th><th>Date</th><th>Status</th></tr></thead>
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
            <tr><td colspan="6" style="text-align:center;color:#666;">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateTransactions() {
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || '';
    const url = '{{ route("transactions.index") }}' + (filter ? '?filter=' + filter : '');
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const total = doc.querySelector('#totalTransactions');
            if (total) document.getElementById('totalTransactions').innerHTML = total.innerHTML;
            
            const coins = doc.querySelector('#totalCoins');
            if (coins) document.getElementById('totalCoins').innerHTML = coins.innerHTML;
            
            const minutes = doc.querySelector('#totalMinutes');
            if (minutes) document.getElementById('totalMinutes').innerHTML = minutes.innerHTML;
            
            const completed = doc.querySelector('#completedTransactions');
            if (completed) document.getElementById('completedTransactions').innerHTML = completed.innerHTML;
            
            const table = doc.querySelector('#transactionsTable');
            if (table) document.getElementById('transactionsTable').innerHTML = table.innerHTML;
            
            const now = new Date();
            document.getElementById('lastUpdate').textContent = '(Updated: ' + now.toLocaleTimeString() + ')';
        })
        .catch(err => console.error('Update failed:', err));
}

updateInterval = setInterval(updateTransactions, 3000);

window.addEventListener('beforeunload', () => {
    if (updateInterval) clearInterval(updateInterval);
});
</script>
@endsection
