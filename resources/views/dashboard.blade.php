@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 25px;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.stat-card:nth-child(2) {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.stat-card:nth-child(3) {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.stat-card:nth-child(4) {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}
.stat-card:hover {
    transform: translateY(-5px);
}
.stat-card .value {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 5px;
}
.stat-card .label {
    font-size: 14px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}
.card h3 {
    margin: 0 0 20px 0;
    font-size: 20px;
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
table {
    width: 100%;
    border-collapse: collapse;
}
thead tr {
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
}
th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: background 0.2s;
}
tbody tr:hover {
    background: #f7fafc;
}
td {
    padding: 15px;
    color: #2d3748;
    font-size: 14px;
}
.badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.badge-success {
    background: #c6f6d5;
    color: #22543d;
}
.badge-warning {
    background: #fed7d7;
    color: #742a2a;
}
.badge-danger {
    background: #e2e8f0;
    color: #4a5568;
}
.machine-time {
    font-family: 'Courier New', monospace;
    font-size: 16px;
    font-weight: 700;
    color: #2d3748;
}
.machine-time.countdown {
    color: #e53e3e;
}
.machine-time.ready {
    color: #38a169;
}
.machine-time.offline {
    color: #718096;
}
#lastUpdate {
    font-size: 11px;
    color: #a0aec0;
    font-weight: 400;
}
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="value" id="todayEarnings">₱{{ number_format($todayEarnings, 2) }}</div>
        <div class="label">Today's Earnings</div>
    </div>
    <div class="stat-card">
        <div class="value" id="todayTransactions">{{ $todayTransactions }}</div>
        <div class="label">Transactions Today</div>
    </div>
    <div class="stat-card">
        <div class="value" id="avgTransaction">₱{{ number_format($avgTransaction, 2) }}</div>
        <div class="label">Avg Transaction</div>
    </div>
    <div class="stat-card">
        <div class="value" id="totalMachines">{{ count($machines) }}</div>
        <div class="label">Total Machines</div>
    </div>
</div>

<div class="card">
    <h3>
        <span>🖥️ Machine Status</span>
        <span id="lastUpdate">(Updated: {{ now()->format('H:i:s') }})</span>
    </h3>
    <table>
        <thead>
            <tr>
                <th>Machine</th>
                <th>Status</th>
                <th>Time Remaining</th>
                <th>Last Seen</th>
            </tr>
        </thead>
        <tbody id="machineStatusTable">
        @forelse($machines as $machine)
            <tr data-machine="{{ $machine['id'] }}" data-seconds="{{ $machine['displayType'] === 'countdown' ? $machine['time'] : 0 }}">
                <td><strong>{{ $machine['id'] }}</strong></td>
                <td>
                    @if($machine['status'] === 'online')
                        <span class="badge badge-success">🟢 Online</span>
                    @elseif($machine['status'] === 'busy')
                        <span class="badge badge-warning">🔴 Busy</span>
                    @else
                        <span class="badge badge-danger">⚫ Offline</span>
                    @endif
                </td>
                <td>
                    @if($machine['displayType'] === 'countdown')
                        <span class="machine-time countdown" data-type="countdown">{{ gmdate('H:i:s', $machine['time']) }}</span>
                    @else
                        <span class="machine-time {{ $machine['status'] === 'online' ? 'ready' : 'offline' }}" data-type="text">{{ $machine['time'] }}</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#888;">{{ $machine['last_heartbeat'] }}</td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;color:#666;">No machines registered yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>📊 Recent Transactions</h3>
    <table>
        <thead><tr><th>PC Unit</th><th>Coins</th><th>Minutes</th><th>Date</th><th>Status</th></tr></thead>
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
            <tr><td colspan="5" style="text-align:center;color:#666;">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;
let countdownInterval;

// Local countdown for each machine
function startLocalCountdowns() {
    if (countdownInterval) clearInterval(countdownInterval);
    
    countdownInterval = setInterval(() => {
        document.querySelectorAll('tr[data-machine]').forEach(row => {
            let seconds = parseInt(row.getAttribute('data-seconds'));
            if (seconds > 0) {
                seconds--;
                row.setAttribute('data-seconds', seconds);
                
                const timeSpan = row.querySelector('.machine-time[data-type="countdown"]');
                if (timeSpan) {
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const secs = seconds % 60;
                    timeSpan.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }
            }
        });
    }, 1000);
}

async function updateDashboard() {
    try {
        const response = await fetch('/dashboard/realtime');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update stats
            document.getElementById('todayEarnings').textContent = '₱' + Number(data.todayEarnings).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('todayTransactions').textContent = data.todayTransactions;
            document.getElementById('avgTransaction').textContent = '₱' + Number(data.avgTransaction).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('totalMachines').textContent = data.machines.length;
            
            // Update machine status table
            const tbody = document.getElementById('machineStatusTable');
            if (data.machines.length > 0) {
                tbody.innerHTML = data.machines.map(machine => {
                    let statusBadge = '';
                    if (machine.status === 'online') {
                        statusBadge = '<span class="badge badge-success">🟢 Online</span>';
                    } else if (machine.status === 'busy') {
                        statusBadge = '<span class="badge badge-warning">🔴 Busy</span>';
                    } else {
                        statusBadge = '<span class="badge badge-danger">⚫ Offline</span>';
                    }
                    
                    let timeDisplay = '';
                    let dataSeconds = 0;
                    
                    if (machine.displayType === 'countdown') {
                        const seconds = machine.time;
                        dataSeconds = seconds;
                        const hours = Math.floor(seconds / 3600);
                        const minutes = Math.floor((seconds % 3600) / 60);
                        const secs = seconds % 60;
                        const timeStr = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                        timeDisplay = `<span class="machine-time countdown" data-type="countdown">${timeStr}</span>`;
                    } else {
                        const cssClass = machine.status === 'online' ? 'ready' : 'offline';
                        timeDisplay = `<span class="machine-time ${cssClass}" data-type="text">${machine.time}</span>`;
                    }
                    
                    return `
                        <tr data-machine="${machine.id}" data-seconds="${dataSeconds}">
                            <td><strong>${machine.id}</strong></td>
                            <td>${statusBadge}</td>
                            <td>${timeDisplay}</td>
                            <td style="font-size:12px;color:#888;">${machine.last_heartbeat}</td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#666;">No machines registered yet.</td></tr>';
            }
            
            // Update recent transactions table
            const txnTbody = document.getElementById('recentTransactionsTable');
            if (data.recentTransactions && data.recentTransactions.length > 0) {
                txnTbody.innerHTML = data.recentTransactions.map(txn => {
                    const statusBadge = txn.status === 'completed' 
                        ? '<span class="badge badge-success">Completed</span>'
                        : '<span class="badge badge-warning">Active</span>';
                    
                    return `
                        <tr>
                            <td>${txn.terminal}</td>
                            <td>₱${Number(txn.amount).toFixed(2)}</td>
                            <td>${txn.minutes || 0} min</td>
                            <td>${new Date(txn.date_and_time).toLocaleString('en-PH', {month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                }).join('');
            }
            
            // Update timestamp
            const now = new Date();
            document.getElementById('lastUpdate').textContent = '(Updated: ' + now.toLocaleTimeString('en-PH', {hour: '2-digit', minute: '2-digit', second: '2-digit'}) + ')';
        }
    } catch (error) {
        console.error('Dashboard update failed:', error);
    }
}

// Start local countdowns immediately
startLocalCountdowns();

// Update from server every 3 seconds
updateInterval = setInterval(updateDashboard, 3000);

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (updateInterval) clearInterval(updateInterval);
    if (countdownInterval) clearInterval(countdownInterval);
});
</script>
@endsection
