@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value" id="todayEarnings">₱{{ number_format($todayEarnings, 2) }}</div>
        <div class="stat-label">Today's Earnings</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="todayTransactions">{{ $todayTransactions }}</div>
        <div class="stat-label">Transactions Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="avgTransaction">₱{{ number_format($avgTransaction, 2) }}</div>
        <div class="stat-label">Avg Transaction</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalMachines">{{ count($machines) }}</div>
        <div class="stat-label">Total Machines</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>▣ Machine Status <span class="update-ts" id="lastUpdate"></span></h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Machine</th>
                <th>Status</th>
                <th>Last Seen</th>
            </tr>
        </thead>
        <tbody id="machineStatusTable">
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
            <tr><td colspan="3" class="td-empty">No machines registered yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h3>◈ Recent Transactions</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>PC Unit</th>
                <th>Coins</th>
                <th>Minutes</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="recentTransactionsTable">
        @forelse($recentTransactions as $txn)
            <tr>
                <td>{{ $txn->pc_unit_id }}</td>
                <td>₱{{ number_format($txn->total_coins, 2) }}</td>
                <td>{{ $txn->total_minutes }} min</td>
                <td>{{ $txn->transaction_date }}</td>
                <td><span class="badge {{ $txn->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($txn->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="5" class="td-empty">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateDashboard() {
    fetch('{{ route("admin.dashboard") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(response => {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const ids = ['todayEarnings', 'todayTransactions', 'avgTransaction',
                         'totalMachines', 'machineStatusTable', 'recentTransactionsTable'];

            ids.forEach(id => {
                const src = doc.getElementById(id);
                const dst = document.getElementById(id);
                if (src && dst) dst.innerHTML = src.innerHTML;
            });

            const now = new Date();
            const el = document.getElementById('lastUpdate');
            if (el) el.textContent = 'Updated ' + now.toLocaleTimeString();
        })
        .catch(err => console.error('Dashboard update failed:', err));
}

updateInterval = setInterval(updateDashboard, 5000);

window.addEventListener('beforeunload', () => {
    if (updateInterval) clearInterval(updateInterval);
});
</script>
@endsection
