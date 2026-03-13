@extends('layouts.admin')

@section('title', 'Reports')

@section('extra-styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="stats-row">
  <div class="stat-card c1">
    <div class="stat-label">Total Earnings Today</div>
    <div class="stat-value">₱{{ number_format($totalEarningsToday, 2) }}</div>
    <div class="stat-meta">{{ $totalEarningsToday > 0 ? 'Active' : 'No earnings yet' }}</div>
    <div class="stat-icon">💰</div>
  </div>
  
  <div class="stat-card c2">
    <div class="stat-label">Total Coins Inserted</div>
    <div class="stat-value">{{ number_format($totalCoinsToday) }}</div>
    <div class="stat-meta">{{ $totalCoinsToday > 0 ? 'Today' : 'No coins yet' }}</div>
    <div class="stat-icon">🪙</div>
  </div>
  
  <div class="stat-card c3">
    <div class="stat-label">Active Machines</div>
    <div class="stat-value">{{ $activeMachines }}</div>
    <div class="stat-meta">Online now</div>
    <div class="stat-icon">💻</div>
  </div>
  
  <div class="stat-card c4">
    <div class="stat-label">Total Sessions Today</div>
    <div class="stat-value">{{ $totalSessionsToday }}</div>
    <div class="stat-meta">{{ $totalSessionsToday > 0 ? 'Active' : 'No sessions yet' }}</div>
    <div class="stat-icon">⚡</div>
  </div>
</div>

<!-- Charts Section -->
<div class="mid-row">
  <div class="glass-card">
    <div class="card-header">
      <div>
        <div class="card-title">Daily Earnings <span>Chart</span></div>
        <div class="card-sub">Last 7 days revenue overview</div>
      </div>
    </div>
    @if($dailyEarnings->isEmpty())
      <div style="padding: 60px 20px; text-align: center; color: var(--text-mid);">
        <div style="font-size: 48px; margin-bottom: 12px; opacity: 0.3;">📊</div>
        <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">No Earnings Data</div>
        <div style="font-size: 12px;">Start accepting payments to see earnings chart</div>
      </div>
    @else
      <canvas id="earningsChart" height="80"></canvas>
    @endif
  </div>
  
  <div class="glass-card">
    <div class="card-header">
      <div>
        <div class="card-title">Machine <span>Usage</span></div>
        <div class="card-sub">Top performing machines</div>
      </div>
    </div>
    @if($machineUsage->isEmpty())
      <div style="padding: 60px 20px; text-align: center; color: var(--text-mid);">
        <div style="font-size: 48px; margin-bottom: 12px; opacity: 0.3;">💻</div>
        <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">No Machine Data</div>
        <div style="font-size: 12px;">Add machines to see usage statistics</div>
      </div>
    @else
      <canvas id="machineChart" height="80"></canvas>
    @endif
  </div>
</div>

<!-- Quick Links -->
<div class="bot-row">
  <div class="glass-card">
    <div class="card-header">
      <div>
        <div class="card-title">Earnings <span>Report</span></div>
        <div class="card-sub">View detailed earnings breakdown</div>
      </div>
    </div>
    <a href="{{ route('reports.earnings') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(90deg, var(--neon2), var(--neon)); border-radius: 8px; color: white; text-decoration: none; font-weight: 600; font-size: 13px;">View Report →</a>
  </div>
  
  <div class="glass-card">
    <div class="card-header">
      <div>
        <div class="card-title">Machine <span>Report</span></div>
        <div class="card-sub">View machine usage statistics</div>
      </div>
    </div>
    <a href="{{ route('reports.machines') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(90deg, var(--neon2), var(--neon)); border-radius: 8px; color: white; text-decoration: none; font-weight: 600; font-size: 13px;">View Report →</a>
  </div>
  
  <div class="glass-card">
    <div class="card-header">
      <div>
        <div class="card-title">Session <span>History</span></div>
        <div class="card-sub">View all session logs</div>
      </div>
    </div>
    <a href="{{ route('reports.sessions') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(90deg, var(--neon2), var(--neon)); border-radius: 8px; color: white; text-decoration: none; font-weight: 600; font-size: 13px;">View Report →</a>
  </div>
</div>
@endsection

@section('scripts')
<script>
@if(!$dailyEarnings->isEmpty())
// Daily Earnings Chart
const earningsCtx = document.getElementById('earningsChart').getContext('2d');
new Chart(earningsCtx, {
  type: 'line',
  data: {
    labels: {!! json_encode($dailyEarnings->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
    datasets: [{
      label: 'Earnings (₱)',
      data: {!! json_encode($dailyEarnings->pluck('total')) !!},
      borderColor: '#00c8ff',
      backgroundColor: 'rgba(0, 200, 255, 0.1)',
      tension: 0.4,
      fill: true,
      borderWidth: 2,
      pointRadius: 4,
      pointHoverRadius: 6
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(4, 14, 28, 0.9)',
        titleColor: '#00c8ff',
        bodyColor: '#e8f4ff',
        borderColor: '#00c8ff',
        borderWidth: 1,
        padding: 12,
        displayColors: false,
        callbacks: {
          label: function(context) {
            return '₱' + context.parsed.y.toFixed(2);
          }
        }
      }
    },
    scales: {
      y: { 
        beginAtZero: true,
        ticks: { 
          color: '#7fa8c9',
          callback: function(value) {
            return '₱' + value;
          }
        },
        grid: { color: 'rgba(0, 200, 255, 0.1)' }
      },
      x: { 
        ticks: { color: '#7fa8c9' },
        grid: { display: false }
      }
    }
  }
});
@endif

@if(!$machineUsage->isEmpty())
// Machine Usage Chart
const machineCtx = document.getElementById('machineChart').getContext('2d');
new Chart(machineCtx, {
  type: 'bar',
  data: {
    labels: {!! json_encode($machineUsage->pluck('machine_name')->take(5)) !!},
    datasets: [{
      label: 'Sessions',
      data: {!! json_encode($machineUsage->pluck('sessions_count')->take(5)) !!},
      backgroundColor: 'rgba(0, 200, 255, 0.6)',
      borderColor: '#00c8ff',
      borderWidth: 1,
      borderRadius: 6
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(4, 14, 28, 0.9)',
        titleColor: '#00c8ff',
        bodyColor: '#e8f4ff',
        borderColor: '#00c8ff',
        borderWidth: 1,
        padding: 12,
        displayColors: false
      }
    },
    scales: {
      y: { 
        beginAtZero: true,
        ticks: { 
          color: '#7fa8c9',
          stepSize: 1
        },
        grid: { color: 'rgba(0, 200, 255, 0.1)' }
      },
      x: { 
        ticks: { color: '#7fa8c9' },
        grid: { display: false }
      }
    }
  }
});
@endif
</script>
@endsection
