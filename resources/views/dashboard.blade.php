
@extends('layouts.admin')

@section('title', 'PisoNet Central — Admin Dashboard')

@section('content')
    <!-- STATS ROW -->
    <div class="stats-row">
      <div class="stat-card c1">
        <div class="stat-label">TOTAL EARNINGS TODAY</div>
        <div class="stat-value" id="todayEarnings">₱{{ number_format($todayEarnings, 2, '.', ',') }}</div>
        <div class="stat-meta">
            <span class="{{ $earningsChange >= 0 ? 'up' : 'down' }}" id="earningsChange">
                {{ $earningsChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($earningsChange), 1) }}%
            </span>
            <span style="color:var(--text-lo)">vs yesterday</span>
        </div>
        <div class="stat-icon">💵</div>
      </div>
      <div class="stat-card c2">
        <div class="stat-label">ACTIVE USERS NOW</div>
        <div class="stat-value" id="activeUsers">{{ count($machines) - collect($machines)->where('status', 'offline')->count() }}</div>
        <div class="stat-meta"><span class="up">▲ <span id="activeUsersChange">0</span></span><span style="color:var(--text-lo)">from last hour</span></div>
        <div class="stat-icon">👥</div>
      </div>
      <div class="stat-card c3">
        <div class="stat-label">MACHINES ONLINE</div>
        <div class="stat-value" id="machinesOnline">{{ count($machines) - collect($machines)->where('status', 'offline')->count() }}<span class="unit">/{{ count($machines) }}</span></div>
        <div class="stat-meta">
            <span class="{{ collect($machines)->where('status', 'offline')->count() > 0 ? 'down' : 'up' }}" id="machinesOffline">
                {{ collect($machines)->where('status', 'offline')->count() > 0 ? '▼' : '▲' }} {{ collect($machines)->where('status', 'offline')->count() }}
            </span>
            <span style="color:var(--text-lo)">offline</span>
        </div>
        <div class="stat-icon">🖥</div>
      </div>
      <div class="stat-card c4">
        <div class="stat-label">TRANSACTIONS TODAY</div>
        <div class="stat-value" id="todayTransactions">{{ $todayTransactions }}</div>
        <div class="stat-meta">
            <span class="{{ $transactionChange >= 0 ? 'up' : 'down' }}" id="transactionChange">
                {{ $transactionChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($transactionChange), 1) }}%
            </span>
            <span style="color:var(--text-lo)">avg ₱{{ number_format($avgTransaction, 2) }}/txn</span>
        </div>
        <div class="stat-icon">🧾</div>
      </div>
    </div>

    <!-- DATASET ROW - System Summary -->
    <div class="stats-row" style="margin-top: 20px;">
      <div class="stat-card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="stat-label">TOTAL PC UNITS</div>
        <div class="stat-value" id="totalPcUnits">--</div>
        <div class="stat-meta">
            <span style="color:var(--text-lo)">System capacity</span>
        </div>
        <div class="stat-icon">🖥️</div>
      </div>
      <div class="stat-card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="stat-label">ACTIVE PC UNITS</div>
        <div class="stat-value" id="activePcUnits">--</div>
        <div class="stat-meta">
            <span style="color:var(--text-lo)">Currently active</span>
        </div>
        <div class="stat-icon">⚡</div>
      </div>
      <div class="stat-card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="stat-label">AVAILABLE PC UNITS</div>
        <div class="stat-value" id="availablePcUnits">--</div>
        <div class="stat-meta">
            <span style="color:var(--text-lo)">Ready to use</span>
        </div>
        <div class="stat-icon">✅</div>
      </div>
      <div class="stat-card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="stat-label">SYSTEM STATUS</div>
        <div class="stat-value" id="systemStatus">--</div>
        <div class="stat-meta">
            <span style="color:var(--text-lo)" id="systemStatusText">Checking...</span>
        </div>
        <div class="stat-icon" id="systemStatusIcon">🔄</div>
      </div>
    </div>

    <!-- DATASET ROW 2 - Sessions and Coins -->
    <div class="stats-row" style="margin-top: 20px;">
      <div class="stat-card" style="background: linear-gradient(135deg, #2d1b4e 0%, #1a1a2e 100%);">
        <div class="stat-label">SESSIONS TODAY</div>
        <div class="stat-value" id="totalSessionsToday">--</div>
        <div class="stat-meta">
            <span style="color:var(--text-lo)" id="sessionsChangeText">--</span>
        </div>
        <div class="stat-icon">🎮</div>
      </div>
      <div class="stat-card" style="background: linear-gradient(135deg, #2d1b4e 0%, #1a1a2e 100%);">
        <div class="stat-label">COINS INSERTED TODAY</div>
        <div class="stat-value" id="coinsInsertedToday">--</div>
        <div class="stat-meta">
            <span style="color:var(--text-lo)">Total coin value</span>
        </div>
        <div class="stat-icon">🪙</div>
      </div>
    </div>

    <!-- MID ROW -->
    <div class="mid-row">
      <!-- Machine Status Grid -->
      <div class="glass-card" style="flex: 1;">
        <div class="card-header">
          <div>
            <div class="card-title">Machine <span>Status</span></div>
            <div class="card-sub">Real-time view — {{ count($machines) }} terminals</div>
          </div>
          <div class="pills">
            <div class="pill active">All</div>
            <div class="pill inactive">Online</div>
            <div class="pill inactive">Offline</div>
          </div>
        </div>
        @if(count($machines) > 0)
        <div class="machine-grid" id="machineGrid"></div>
        @else
        <div class="empty-state" style="padding: 40px 20px;">
          <div class="empty-icon">🖥️</div>
          <div class="empty-title">No PC Units Configured</div>
          <div class="empty-sub">Add PC units to see machine status</div>
        </div>
        @endif
      </div>

      <!-- Revenue Breakdown -->
      <div class="glass-card">
        <div class="card-header">
          <div>
            <div class="card-title">Revenue <span>Breakdown</span></div>
            <div class="card-sub">By session type</div>
          </div>
        </div>
        @if($totalTodayEarnings > 0)
        <div class="donut-wrap">
          <svg class="donut-svg" width="130" height="130" viewBox="0 0 130 130">
            <!-- segments -->
            <circle cx="65" cy="65" r="50" fill="none" stroke="rgba(0,200,255,0.08)" stroke-width="18"/>
            <circle cx="65" cy="65" r="50" fill="none" stroke="url(#g1)" stroke-width="18"
              stroke-dasharray="188 126" stroke-dashoffset="-30" stroke-linecap="round"/>
            <circle cx="65" cy="65" r="50" fill="none" stroke="url(#g2)" stroke-width="18"
              stroke-dasharray="82 232" stroke-dashoffset="-218" stroke-linecap="round"/>
            <circle cx="65" cy="65" r="50" fill="none" stroke="rgba(255,180,0,0.8)" stroke-width="18"
              stroke-dasharray="38 276" stroke-dashoffset="-300" stroke-linecap="round"/>
            <defs>
              <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#0077ff"/>
                <stop offset="100%" stop-color="#00c8ff"/>
              </linearGradient>
              <linearGradient id="g2" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#9b59b6"/>
                <stop offset="100%" stop-color="#e91e8c"/>
              </linearGradient>
            </defs>
            <text x="65" y="60" class="donut-center" fill="#e8f4ff" font-size="18" font-family="Syne" font-weight="800">₱{{ number_format($totalTodayEarnings, 2, '.', ',') }}</text>
            <text x="65" y="77" class="donut-center" fill="#7fa8c9" font-size="9" font-family="DM Mono">TODAY TOTAL</text>
          </svg>
          <div class="donut-legend">
            <div class="dl-item">
              <div class="dl-name" style="color:#00c8ff">Gaming</div>
              <div class="dl-bar-wrap"><div class="dl-bar" style="width:60%;background:linear-gradient(90deg,#0077ff,#00c8ff)"></div></div>
              <div class="dl-pct">60%</div>
            </div>
            <div class="dl-item">
              <div class="dl-name" style="color:#e91e8c">Browsing</div>
              <div class="dl-bar-wrap"><div class="dl-bar" style="width:26%;background:linear-gradient(90deg,#9b59b6,#e91e8c)"></div></div>
              <div class="dl-pct">26%</div>
            </div>
            <div class="dl-item">
              <div class="dl-name" style="color:#ffb400">Printing</div>
              <div class="dl-bar-wrap"><div class="dl-bar" style="width:14%;background:#ffb400"></div></div>
              <div class="dl-pct">14%</div>
            </div>
          </div>
        </div>
        @else
        <div class="empty-state" style="padding: 40px 20px;">
          <div class="empty-icon">📊</div>
          <div class="empty-title">No Earnings Data</div>
          <div class="empty-sub">Revenue breakdown will appear here</div>
        </div>
        @endif
      </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ── Weekly Chart (Chart.js) ──
const weeklyLabels = @json($weeklyLabels);
const weeklyEarnings = @json($weeklyEarnings);

const ctx = document.getElementById('weeklyChart').getContext('2d');
const weeklyChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: weeklyLabels,
        datasets: [{
            label: 'Earnings (₱)',
            data: weeklyEarnings,
            backgroundColor: 'rgba(0, 200, 255, 0.6)',
            borderColor: 'rgba(0, 200, 255, 1)',
            borderWidth: 1,
            borderRadius: 4,
            barThickness: 30,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.1)' },
                ticks: { color: '#7fa8c9' }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#7fa8c9' }
            }
        }
    }
});

// ── Real-time Data Fetch ──
let previousActiveUsers = {{ count($machines) - collect($machines)->where('status', 'offline')->count() }};

async function fetchRealtimeData() {
    try {
        const response = await fetch('{{ route("dashboard.realtime") }}');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update stats
            document.getElementById('todayEarnings').textContent = '₱' + data.todayEarnings.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const earningsChangeEl = document.getElementById('earningsChange');
            earningsChangeEl.className = data.earningsChange >= 0 ? 'up' : 'down';
            earningsChangeEl.innerHTML = (data.earningsChange >= 0 ? '▲' : '▼') + ' ' + Math.abs(data.earningsChange).toFixed(1) + '%';
            
            document.getElementById('todayTransactions').textContent = data.todayTransactions;
            
            const txnChangeEl = document.getElementById('transactionChange');
            txnChangeEl.className = data.transactionChange >= 0 ? 'up' : 'down';
            txnChangeEl.innerHTML = (data.transactionChange >= 0 ? '▲' : '▼') + ' ' + Math.abs(data.transactionChange).toFixed(1) + '%';
            
            // Update machines
            const onlineCount = data.machines.filter(m => m.status !== 'offline').length;
            const offlineCount = data.machines.filter(m => m.status === 'offline').length;
            document.getElementById('machinesOnline').innerHTML = onlineCount + '<span class="unit">/20</span>';
            
            const offlineEl = document.getElementById('machinesOffline');
            offlineEl.className = offlineCount > 0 ? 'down' : 'up';
            offlineEl.innerHTML = (offlineCount > 0 ? '▼' : '▲') + ' ' + offlineCount;
            
            // Update active users
            const activeUsersEl = document.getElementById('activeUsers');
            const newActive = data.machines.filter(m => m.status === 'busy' || m.status === 'online').length;
            activeUsersEl.textContent = newActive;
            
            const activeChange = newActive - previousActiveUsers;
            document.getElementById('activeUsersChange').textContent = Math.abs(activeChange);
            previousActiveUsers = newActive;
            
            // Update machine grid
            const grid = document.getElementById('machineGrid');
            grid.innerHTML = '';
            data.machines.forEach(m => {
                grid.innerHTML += `
                <div class="machine ${m.status}">
                    <div class="machine-icon">${icons[m.status]}</div>
                    <div class="machine-id">${m.id}</div>
                    <div class="machine-time">${m.time}</div>
                    <div class="machine-status">${m.status.toUpperCase()}</div>
                </div>`;
            });
            
            // Update activity feed
            const feed = document.getElementById('activityFeed');
            feed.innerHTML = '';
            data.activities.forEach(a => {
                feed.innerHTML += `
                <div class="act-item">
                    <div class="act-dot" style="background:${a.dot};box-shadow:0 0 6px ${a.dot}"></div>
                    <div class="act-content">
                    <div class="act-title">${a.title}</div>
                    <div class="act-sub">${a.sub}</div>
                    </div>
                    <div class="act-time">${a.time}</div>
                </div>`;
            });
            
            // Update transactions
            const tbody = document.getElementById('txnTable');
            tbody.innerHTML = '';
            data.recentTransactions.forEach(t => {
                const time = new Date(t.date_and_time).toLocaleTimeString('en-PH', {hour: '2-digit', minute: '2-digit', hour12: false});
                tbody.innerHTML += `
                <tr>
                    <td class="txn-machine">${t.terminal}</td>
                    <td class="txn-user">--</td>
                    <td class="txn-amount">₱${t.amount.toFixed(2)}</td>
                    <td class="txn-dur">--</td>
                    <td style="color:var(--text-mid);font-family:'DM Mono',monospace;font-size:11px">${time}</td>
                    <td><span class="txn-badge ${t.status==='completed'?'badge-paid':'badge-pend'}">${t.status.toUpperCase()}</span></td>
                </tr>`;
            });
            
            // Update donut chart
            document.querySelector('.donut-center').textContent = '₱' + data.totalTodayEarnings.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const legendItems = document.querySelectorAll('.dl-item');
            if (legendItems.length >= 3) {
                legendItems[0].querySelector('.dl-pct').textContent = data.gamingPercent.toFixed(0) + '%';
                legendItems[0].querySelector('.dl-bar').style.width = data.gamingPercent + '%';
                legendItems[1].querySelector('.dl-pct').textContent = data.browsingPercent.toFixed(0) + '%';
                legendItems[1].querySelector('.dl-bar').style.width = data.browsingPercent + '%';
                legendItems[2].querySelector('.dl-pct').textContent = data.printingPercent.toFixed(0) + '%';
                legendItems[2].querySelector('.dl-bar').style.width = data.printingPercent + '%';
            }
            
            const circumference = 2 * Math.PI * 50;
            const gamingDash = (data.gamingPercent / 100) * circumference;
            const browsingDash = (data.browsingPercent / 100) * circumference;
            const printingDash = (data.printingPercent / 100) * circumference;
            
            const donutSegments = document.querySelectorAll('.donut-svg circle');
            if (donutSegments.length >= 4) {
                donutSegments[1].setAttribute('stroke-dasharray', `${gamingDash} ${circumference - gamingDash}`);
                donutSegments[2].setAttribute('stroke-dasharray', `${browsingDash} ${circumference - browsingDash}`);
                donutSegments[2].setAttribute('stroke-dashoffset', -gamingDash);
                donutSegments[3].setAttribute('stroke-dasharray', `${printingDash} ${circumference - printingDash}`);
                donutSegments[3].setAttribute('stroke-dashoffset', -(gamingDash + browsingDash));
            }
            
            // Update weekly chart
            weeklyChart.data.datasets[0].data = data.weeklyEarnings;
            weeklyChart.data.labels = data.weeklyLabels;
            weeklyChart.update();
            
            console.log('Dashboard refreshed:', result.timestamp);
        }
    } catch (error) {
        console.error('Error fetching real-time data:', error);
    }
}

// Auto-refresh every 10 seconds
setInterval(fetchRealtimeData, 10000);

// Initial data load
fetchRealtimeData();

// ── Fetch Dashboard Dataset ──
async function fetchDataset() {
    try {
        const response = await fetch('{{ route("dashboard.dataset") }}');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update dataset fields
            document.getElementById('totalPcUnits').textContent = data.total_pc_units;
            document.getElementById('activePcUnits').textContent = data.active_pc_units;
            document.getElementById('availablePcUnits').textContent = data.available_pc_units;
            document.getElementById('totalSessionsToday').textContent = data.total_sessions_today;
            document.getElementById('coinsInsertedToday').textContent = '₱' + data.coins_inserted_today.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Update system status with styling
            const systemStatusEl = document.getElementById('systemStatus');
            const systemStatusText = document.getElementById('systemStatusText');
            const systemStatusIcon = document.getElementById('systemStatusIcon');
            
            const statusConfig = {
                'optimal': { color: '#00e676', text: 'All systems operational', icon: '✅' },
                'operational': { color: '#00c8ff', text: 'System operational', icon: '🟢' },
                'degraded': { color: '#ffb400', text: 'System degraded', icon: '⚠️' },
                'offline': { color: '#ff4444', text: 'System offline', icon: '🔴' }
            };
            
            const status = statusConfig[data.system_status] || statusConfig['operational'];
            systemStatusEl.textContent = data.system_status.toUpperCase();
            systemStatusEl.style.color = status.color;
            systemStatusText.textContent = status.text;
            systemStatusText.style.color = status.color;
            systemStatusIcon.textContent = status.icon;
            
            // Update sessions change
            const sessionsChangeText = document.getElementById('sessionsChangeText');
            if (data.sessions_change !== 0) {
                const changeSymbol = data.sessions_change >= 0 ? '▲' : '▼';
                sessionsChangeText.textContent = changeSymbol + ' ' + Math.abs(data.sessions_change).toFixed(1) + '% vs yesterday';
                sessionsChangeText.className = data.sessions_change >= 0 ? 'up' : 'down';
            } else {
                sessionsChangeText.textContent = 'No change from yesterday';
            }
            
            console.log('Dataset refreshed:', result.timestamp);
        }
    } catch (error) {
        console.error('Error fetching dataset:', error);
    }
}

// Auto-refresh dataset every 30 seconds
setInterval(fetchDataset, 30000);

// Initial dataset load
fetchDataset();

// ── Machine Grid (Real Data from Controller) ──
const icons = {busy:'🟡',online:'🟢',offline:'⚫',error:'🔴'};
const machines = @json($machines);
const grid = document.getElementById('machineGrid');
machines.forEach(m => {
  grid.innerHTML += `
  <div class="machine ${m.status}">
    <div class="machine-icon">${icons[m.status]}</div>
    <div class="machine-id">${m.id}</div>
    <div class="machine-time">${m.time}</div>
    <div class="machine-status">${m.status.toUpperCase()}</div>
  </div>`;
});

// ── Activity Feed (Real Data from Controller) ──
// Already populated via Blade template, JS updates for real-time refresh only
const activities = @json($activities);

// ── Transactions (Real Data from Controller) ──
// Already populated via Blade template, JS updates for real-time refresh only
const txnsData = @json($recentTransactions);

// ── Revenue Breakdown (Real Data from Controller) ──
const totalToday = {{ $totalTodayEarnings }};
const gamingPercent = {{ $gamingPercent }};
const browsingPercent = {{ $browsingPercent }};
const printingPercent = {{ $printingPercent }};

// Update donut center text
document.querySelector('.donut-center').textContent = '₱' + totalToday.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

// Update legend percentages
const legendItems = document.querySelectorAll('.dl-item');
if (legendItems.length >= 3) {
    legendItems[0].querySelector('.dl-pct').textContent = gamingPercent.toFixed(0) + '%';
    legendItems[0].querySelector('.dl-bar').style.width = gamingPercent + '%';
    legendItems[1].querySelector('.dl-pct').textContent = browsingPercent.toFixed(0) + '%';
    legendItems[1].querySelector('.dl-bar').style.width = browsingPercent + '%';
    legendItems[2].querySelector('.dl-pct').textContent = printingPercent.toFixed(0) + '%';
    legendItems[2].querySelector('.dl-bar').style.width = printingPercent + '%';
}

// Update donut chart segments
const circumference = 2 * Math.PI * 50; // 314.159
const gamingDash = (gamingPercent / 100) * circumference;
const browsingDash = (browsingPercent / 100) * circumference;
const printingDash = (printingPercent / 100) * circumference;

const donutSegments = document.querySelectorAll('.donut-svg circle');
if (donutSegments.length >= 4) {
    donutSegments[1].setAttribute('stroke-dasharray', `${gamingDash} ${circumference - gamingDash}`);
    donutSegments[2].setAttribute('stroke-dasharray', `${browsingDash} ${circumference - browsingDash}`);
    donutSegments[2].setAttribute('stroke-dashoffset', -gamingDash);
    donutSegments[3].setAttribute('stroke-dasharray', `${printingDash} ${circumference - printingDash}`);
    donutSegments[3].setAttribute('stroke-dashoffset', -(gamingDash + browsingDash));
}

// ── Tick active timers ──
setInterval(() => {
  document.querySelectorAll('.machine.busy .machine-time, .machine.online .machine-time').forEach(el => {
    const t = el.textContent;
    if (!t.includes(':') || t === '--:--') return;
    let [m,s] = t.split(':').map(Number);
    s++;
    if(s>=60){s=0;m++;}
    if(m>=60)m=0;
    el.textContent = `${m}:${String(s).padStart(2,'0')}`;
  });
}, 1000);
</script>
@endsection

