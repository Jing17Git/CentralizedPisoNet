@extends('layouts.admin')

@section('title', 'Earnings - PisoNet Central')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-title">
            <h1>Earnings <span>& Finance</span></h1>
            <p class="header-sub">View income reports and generate exports</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('earnings.export.pdf', ['filter' => $filter]) }}" class="btn btn-primary">
                <span>📄</span> Export PDF
            </a>
            <a href="{{ route('earnings.export.excel', ['filter' => $filter]) }}" class="btn btn-secondary">
                <span>📊</span> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('earnings.index', ['filter' => 'daily']) }}" 
           class="filter-tab {{ $filter === 'daily' ? 'active' : '' }}">
            <span class="tab-icon">📅</span> Daily
        </a>
        <a href="{{ route('earnings.index', ['filter' => 'weekly']) }}" 
           class="filter-tab {{ $filter === 'weekly' ? 'active' : '' }}">
            <span class="tab-icon">📆</span> Weekly
        </a>
        <a href="{{ route('earnings.index', ['filter' => 'monthly']) }}" 
           class="filter-tab {{ $filter === 'monthly' ? 'active' : '' }}">
            <span class="tab-icon">🗓</span> Monthly
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card c1">
            <div class="stat-label">TOTAL {{ strtoupper($filter) }} EARNINGS</div>
            <div class="stat-value">₱{{ number_format($totalAmount, 2) }}</div>
            <div class="stat-meta">
                <span class="filter-badge">{{ ucfirst($filter) }} Period</span>
            </div>
            <div class="stat-icon">💵</div>
        </div>
        <div class="stat-card c2">
            <div class="stat-label">TOTAL TRANSACTIONS</div>
            <div class="stat-value">{{ $totalTransactions }}</div>
            <div class="stat-meta">
                <span class="up">✓</span> All recorded
            </div>
            <div class="stat-icon">🧾</div>
        </div>
        <div class="stat-card c3">
            <div class="stat-label">COMPLETED</div>
            <div class="stat-value">{{ $completedTransactions }}</div>
            <div class="stat-meta">
                <span class="up">✓</span> Successful
            </div>
            <div class="stat-icon">✅</div>
        </div>
        <div class="stat-card c4">
            <div class="stat-label">AVERAGE PER TRANSACTION</div>
            <div class="stat-value">₱{{ number_format($averageTransaction, 2) }}</div>
            <div class="stat-meta">
                <span class="info">ℹ</span> Per transaction
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>

    <!-- Earnings Table -->
    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="card-title">Earnings <span>Records</span></div>
                <div class="card-sub">Transaction history for {{ ucfirst($filter) }} period</div>
            </div>
            <div class="table-info">
                <span class="record-count">{{ $earnings->count() }} records</span>
            </div>
        </div>

        @if($earnings->count() > 0)
        <div class="table-responsive">
            <table class="txn-table">
                <thead>
                    <tr>
                        <th>TRANSACTION ID</th>
                        <th>TERMINAL</th>
                        <th>TYPE</th>
                        <th>DATE & TIME</th>
                        <th>AMOUNT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($earnings as $earning)
                    <tr>
                        <td class="txn-id">{{ $earning->transaction_id }}</td>
                        <td class="txn-machine">{{ $earning->terminal }}</td>
                        <td class="txn-type">
                            <span class="type-badge type-{{ $earning->type }}">
                                @if($earning->type === 'gaming')🎮
                                @elseif($earning->type === 'browsing')🌐
                                @elseif($earning->type === 'printing')🖨
                                @else💰
                                @endif
                                {{ ucfirst($earning->type) }}
                            </span>
                        </td>
                        <td class="txn-date">
                            {{ $earning->date_and_time->format('M d, Y') }}
                            <span class="txn-time">{{ $earning->date_and_time->format('H:i:s') }}</span>
                        </td>
                        <td class="txn-amount">₱{{ number_format($earning->amount, 2) }}</td>
                        <td>
                            @if($earning->status === 'completed')
                                <span class="txn-badge badge-paid">COMPLETED</span>
                            @elseif($earning->status === 'pending')
                                <span class="txn-badge badge-pend">PENDING</span>
                            @else
                                <span class="txn-badge badge-refunded">REFUNDED</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">No Earnings Found</div>
            <div class="empty-sub">No transactions recorded for this period.</div>
        </div>
        @endif
    </div>
@endsection

@section('extra-styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }
    .header-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-hi);
        margin-bottom: 4px;
    }
    .header-title h1 span {
        color: var(--neon);
    }
    .header-sub {
        font-size: 13px;
        color: var(--text-mid);
    }
    .header-actions {
        display: flex;
        gap: 10px;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        cursor: pointer;
        border: none;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--neon2), var(--neon));
        color: #fff;
        box-shadow: 0 4px 15px rgba(0,200,255,0.3);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,200,255,0.4);
    }
    .btn-secondary {
        background: var(--glass);
        border: 1px solid var(--border);
        color: var(--text-hi);
    }
    .btn-secondary:hover {
        border-color: var(--neon);
        color: var(--neon);
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 22px;
        background: var(--bg-card);
        padding: 6px;
        border-radius: 12px;
        width: fit-content;
        border: 1px solid var(--border);
    }
    .filter-tab {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-mid);
        font-size: 13px;
        font-weight: 500;
        transition: all .2s;
    }
    .filter-tab:hover {
        color: var(--text-hi);
        background: var(--glass);
    }
    .filter-tab.active {
        background: linear-gradient(90deg, rgba(0,120,255,0.22), rgba(0,200,255,0.10));
        color: var(--neon);
        border: 1px solid rgba(0,200,255,0.2);
    }
    .tab-icon {
        font-size: 14px;
    }

    .filter-badge {
        background: rgba(0,200,255,0.15);
        color: var(--neon);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-family: 'DM Mono', monospace;
        text-transform: uppercase;
    }
    .info {
        color: var(--neon);
    }

    .table-info {
        display: flex;
        align-items: center;
    }
    .record-count {
        font-size: 12px;
        color: var(--text-mid);
        font-family: 'DM Mono', monospace;
    }

    .table-responsive {
        overflow-x: auto;
    }
    .txn-id {
        font-family: 'DM Mono', monospace;
        color: var(--neon);
        font-size: 11px;
    }
    .txn-type {
        font-size: 12px;
    }
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    .type-gaming {
        background: rgba(0,200,255,0.15);
        color: var(--neon);
    }
    .type-browsing {
        background: rgba(233,30,140,0.15);
        color: #e91e8c;
    }
    .type-printing {
        background: rgba(255,180,0,0.15);
        color: var(--accent-warn);
    }
    .txn-date {
        font-size: 12px;
        color: var(--text-hi);
    }
    .txn-time {
        display: block;
        font-size: 10px;
        color: var(--text-lo);
        font-family: 'DM Mono', monospace;
        margin-top: 2px;
    }
    .txn-amount {
        font-family: 'DM Mono', monospace;
        font-weight: 700;
        color: var(--accent-grn);
        font-size: 13px;
    }
    .badge-refunded {
        background: rgba(255,59,107,0.15);
        color: var(--accent-red);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    .empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-hi);
        margin-bottom: 6px;
    }
    .empty-sub {
        font-size: 13px;
        color: var(--text-mid);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 16px;
        }
        .header-actions {
            width: 100%;
        }
        .header-actions .btn {
            flex: 1;
            justify-content: center;
        }
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

