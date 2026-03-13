@extends('layouts.admin')

@section('title', 'Transactions - PisoNet Central')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-title">
            <h1>Transactions <span>& Income</span></h1>
            <p class="header-sub">View payment records and generate reports</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('transactions.export.pdf', ['filter' => $filter]) }}" class="btn btn-primary">
                <span>📄</span> Export PDF
            </a>
            <a href="{{ route('transactions.export.excel', ['filter' => $filter]) }}" class="btn btn-secondary">
                <span>📊</span> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('transactions.index', ['filter' => 'daily']) }}" 
           class="filter-tab {{ $filter === 'daily' ? 'active' : '' }}">
            <span class="tab-icon">📅</span> Daily
        </a>
        <a href="{{ route('transactions.index', ['filter' => 'weekly']) }}" 
           class="filter-tab {{ $filter === 'weekly' ? 'active' : '' }}">
            <span class="tab-icon">📆</span> Weekly
        </a>
        <a href="{{ route('transactions.index', ['filter' => 'monthly']) }}" 
           class="filter-tab {{ $filter === 'monthly' ? 'active' : '' }}">
            <span class="tab-icon">🗓</span> Monthly
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card c1">
            <div class="stat-label">TOTAL {{ strtoupper($filter) }} INCOME</div>
            <div class="stat-value">₱{{ number_format($totalCoins, 2) }}</div>
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
            <div class="stat-label">TOTAL MINUTES SOLD</div>
            <div class="stat-value">{{ number_format($totalMinutes) }}</div>
            <div class="stat-meta">
                <span class="up">⏱</span> Time purchased
            </div>
            <div class="stat-icon">⏰</div>
        </div>
        <div class="stat-card c4">
            <div class="stat-label">AVERAGE PER TRANSACTION</div>
            <div class="stat-value">₱{{ number_format($averageCoins, 2) }}</div>
            <div class="stat-meta">
                <span class="info">ℹ</span> Per transaction
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="card-title">Transaction <span>Records</span></div>
                <div class="card-sub">Payment history for {{ ucfirst($filter) }} period</div>
            </div>
            <div class="table-info">
                <span class="record-count">{{ $transactions->count() }} records</span>
            </div>
        </div>

        @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="txn-table">
                <thead>
                    <tr>
                        <th>TRANSACTION ID</th>
                        <th>PC UNIT</th>
                        <th>TOTAL COINS</th>
                        <th>TOTAL MINUTES</th>
                        <th>START TIME</th>
                        <th>END TIME</th>
                        <th>TRANSACTION DATE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="txn-id">{{ $transaction->transaction_id }}</td>
                        <td class="txn-machine">{{ $transaction->pc_unit_id }}</td>
                        <td class="txn-amount">₱{{ number_format($transaction->total_coins, 2) }}</td>
                        <td class="txn-minutes">{{ $transaction->total_minutes }} min</td>
                        <td class="txn-date">
                            @if($transaction->start_time)
                                {{ $transaction->start_time->format('M d, Y') }}
                                <span class="txn-time">{{ $transaction->start_time->format('H:i:s') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="txn-date">
                            @if($transaction->end_time)
                                {{ $transaction->end_time->format('M d, Y') }}
                                <span class="txn-time">{{ $transaction->end_time->format('H:i:s') }}</span>
                            @else
                                <span class="txn-badge badge-pend">ACTIVE</span>
                            @endif
                        </td>
                        <td class="txn-date">
                            {{ $transaction->transaction_date->format('M d, Y') }}
                        </td>
                        <td>
                            @if($transaction->status === 'completed')
                                <span class="txn-badge badge-paid">COMPLETED</span>
                            @elseif($transaction->status === 'active')
                                <span class="txn-badge badge-pend">ACTIVE</span>
                            @else
                                <span class="txn-badge badge-refunded">CANCELLED</span>
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
            <div class="empty-title">No Transactions Found</div>
            <div class="empty-sub">No payment records for this period.</div>
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
    .txn-minutes {
        font-family: 'DM Mono', monospace;
        color: var(--accent-warn);
        font-size: 12px;
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

@section('scripts')
@endsection

