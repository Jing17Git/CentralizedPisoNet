@extends('layouts.app')
@section('title', 'Dashboard Overview')

@section('content')
<!-- DASHBOARD WELCOME HERO -->
<div class="dashboard-hero">
    <div>
        <h2>Welcome back, {{ Auth::user()->name ?? 'Administrator' }}</h2>
        <p>Real-time telemetry, terminal utilization, and centralized revenue metrics.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('pc-units.index') }}" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            <span>Manage Fleet</span>
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            <span>Full Reports</span>
        </a>
    </div>
</div>

<!-- KPI METRIC CARDS -->
<div class="stats-grid">
    <!-- Card 1: Today's Revenue -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 6v12M15 9.5a3.5 3.5 0 0 0-5 0c0 2 3 2.5 3 4.5a3.5 3.5 0 0 1-5 0"></path>
                </svg>
            </div>
            @if(isset($earningsChange))
                <span class="stat-trend {{ $earningsChange >= 0 ? 'positive' : 'negative' }}">
                    {{ $earningsChange >= 0 ? '▲ +' : '▼ ' }}{{ number_format(abs($earningsChange), 1) }}%
                </span>
            @endif
        </div>
        <div class="value">₱{{ number_format($todayEarnings, 2) }}</div>
        <div class="label">Today's Total Revenue</div>
    </div>

    <!-- Card 2: Transactions Today -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                    <circle cx="7" cy="15" r="1"></circle>
                </svg>
            </div>
            @if(isset($transactionChange))
                <span class="stat-trend {{ $transactionChange >= 0 ? 'positive' : 'negative' }}">
                    {{ $transactionChange >= 0 ? '▲ +' : '▼ ' }}{{ number_format(abs($transactionChange), 1) }}%
                </span>
            @endif
        </div>
        <div class="value">{{ number_format($todayTransactions) }}</div>
        <div class="label">Total Transactions Today</div>
    </div>

    <!-- Card 3: Avg Transaction -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                    <polyline points="16 7 22 7 22 13"></polyline>
                </svg>
            </div>
            <span class="stat-trend neutral">Avg / User</span>
        </div>
        <div class="value">₱{{ number_format($avgTransaction, 2) }}</div>
        <div class="label">Average Spend Per User</div>
    </div>

    <!-- Card 4: Fleet Overview -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            @php
                $onlineCount = collect($machines)->where('status', 'online')->count();
                $busyCount = collect($machines)->where('status', 'busy')->count();
            @endphp
            <span class="stat-trend positive">{{ $onlineCount + $busyCount }} Active</span>
        </div>
        <div class="value">{{ count($machines) }} Units</div>
        <div class="label">Total Connected Machines</div>
    </div>
</div>

<!-- 2-COLUMN DASHBOARD CONTENT -->
<div class="dashboard-grid-2col">
    <!-- LEFT COLUMN: Weekly Chart & Recent Transactions -->
    <div>
        <!-- WEEKLY REVENUE CHART -->
        <div class="card">
            <div class="card-header-flex">
                <div>
                    <h3>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <span>7-Day Revenue Trend</span>
                    </h3>
                    <p style="font-size: 12.5px; color: var(--neutral-500); margin-top: 2px;">Daily coin insert collections over the last 7 days</p>
                </div>
                <div style="font-size: 12px; font-weight: 700; color: var(--primary); background: var(--primary-light); padding: 4px 10px; border-radius: 6px;">
                    Past 7 Days
                </div>
            </div>
            <div style="height: 240px; position: relative; width: 100%;">
                <canvas id="weeklyRevenueChart"></canvas>
            </div>
        </div>

        <!-- RECENT TRANSACTIONS TABLE -->
        <div class="card">
            <div class="card-header-flex">
                <h3>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                    <span>Recent Transactions</span>
                </h3>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline btn-sm">View All</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>PC Unit</th>
                            <th>Amount</th>
                            <th>Allocated Time</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentTransactions as $txn)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: var(--neutral-900);">{{ $txn->pc_unit_id }}</span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--primary);">₱{{ number_format($txn->total_coins, 2) }}</span>
                            </td>
                            <td>
                                <span style="color: var(--neutral-600); font-weight: 500;">{{ $txn->total_minutes }} mins</span>
                            </td>
                            <td>
                                <span style="color: var(--neutral-500); font-size: 12.5px;">{{ \Carbon\Carbon::parse($txn->transaction_date)->format('M d, Y · h:i A') }}</span>
                            </td>
                            <td>
                                @if($txn->status === 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($txn->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--neutral-400); padding: 32px 16px;">
                                No recent transactions recorded yet today.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Machine Status & Activity Feed -->
    <div>
        <!-- MACHINE FLEET LIVE MONITOR -->
        <div class="card">
            <div class="card-header-flex">
                <h3>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                    <span>Terminal Fleet</span>
                </h3>
                <a href="{{ route('pc-units.index') }}" class="btn btn-outline btn-sm">Configure</a>
            </div>

            <div class="machine-pill-grid">
                @forelse($machines as $machine)
                    <div class="machine-pill">
                        <div class="machine-pill-header">
                            <span class="machine-pill-name">{{ $machine['id'] }}</span>
                            <span class="machine-pill-dot {{ $machine['status'] }}" title="{{ ucfirst($machine['status']) }}"></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: {{ $machine['status'] === 'online' ? 'var(--success-text)' : ($machine['status'] === 'busy' ? 'var(--warning-text)' : 'var(--danger-text)') }};">
                                {{ $machine['status'] }}
                            </span>
                            <span class="machine-pill-time">{{ $machine['time'] }}</span>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--neutral-400); padding: 24px 0;">
                        No PC terminals configured yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ACTIVITY FEED -->
        <div class="card">
            <div class="card-header-flex">
                <h3>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                    <span>Recent System Activity</span>
                </h3>
            </div>

            <div class="activity-feed">
                @forelse($activities as $activity)
                    <div class="activity-item">
                        <span class="activity-dot" style="background: var(--primary);"></span>
                        <div class="activity-content">
                            <div class="activity-title">{{ $activity['title'] }}</div>
                            <div class="activity-sub">{{ $activity['sub'] }}</div>
                        </div>
                        <div class="activity-time">{{ $activity['time'] }}</div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--neutral-400); padding: 20px 0;">
                        No recent activity logged.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('weeklyRevenueChart');
        if (ctx && typeof Chart !== 'undefined') {
            const labels = @json($weeklyLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
            const data = @json($weeklyEarnings ?? [0, 0, 0, 0, 0, 0, 0]);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Gross Income (₱)',
                        data: data,
                        borderColor: '#1E3A8A',
                        backgroundColor: 'rgba(30, 58, 138, 0.08)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#1E3A8A',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4.5,
                        pointHoverRadius: 6.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 'bold' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return ' ₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#64748B',
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '500' }
                            }
                        },
                        y: {
                            border: { dash: [4, 4] },
                            grid: { color: '#F1F5F9' },
                            ticks: {
                                color: '#64748B',
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                                callback: function(value) {
                                    return '₱' + value;
                                }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
