@extends('layouts.admin')

@section('title', 'Session Monitoring')

@section('extra-styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-hi);
    }
    .page-title span {
        color: var(--neon);
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        border: none;
        font-family: 'Syne', sans-serif;
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
    .btn-danger {
        background: rgba(255,59,107,0.15);
        color: var(--accent-red);
        border: 1px solid rgba(255,59,107,0.3);
    }
    .btn-danger:hover {
        background: rgba(255,59,107,0.25);
    }
    .btn-success {
        background: rgba(0,230,118,0.15);
        color: var(--accent-grn);
        border: 1px solid rgba(0,230,118,0.3);
    }
    .btn-success:hover {
        background: rgba(0,230,118,0.25);
    }
    .btn-warning {
        background: rgba(255,180,0,0.15);
        color: var(--accent-warn);
        border: 1px solid rgba(255,180,0,0.3);
    }
    .btn-warning:hover {
        background: rgba(255,180,0,0.25);
    }
    .btn-sm {
        padding: 6px 12px;
        font-size: 11px;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        backdrop-filter: blur(16px);
    }
    .stat-label {
        font-size: 11px;
        color: var(--text-mid);
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-hi);
    }
    .stat-card.total .stat-value { color: var(--neon); }
    .stat-card.active .stat-value { color: var(--accent-grn); }
    .stat-card.ended .stat-value { color: var(--accent-warn); }

    /* Live Indicator */
    .live-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'DM Mono', monospace;
        font-size: 11px;
        color: var(--accent-grn);
        background: rgba(0,230,118,0.08);
        border: 1px solid rgba(0,230,118,0.2);
        border-radius: 20px;
        padding: 4px 12px;
    }
    .live-indicator::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent-grn);
        box-shadow: 0 0 6px var(--accent-grn);
        animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:.5; transform:scale(1.4); }
    }

    /* Filters */
    .filters-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 16px;
        flex: 1;
        max-width: 300px;
    }
    .search-box input {
        background: none;
        border: none;
        outline: none;
        color: var(--text-hi);
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        width: 100%;
    }
    .search-box input::placeholder {
        color: var(--text-lo);
    }
    .filter-select {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 16px;
        color: var(--text-hi);
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        outline: none;
        cursor: pointer;
    }
    .filter-select:focus {
        border-color: var(--neon);
    }

    /* Table */
    .glass-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        backdrop-filter: blur(16px);
        overflow: hidden;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table th {
        text-align: left;
        font-size: 10px;
        letter-spacing: 1.5px;
        color: var(--text-lo);
        font-family: 'DM Mono', monospace;
        padding: 14px 16px;
        text-transform: uppercase;
        font-weight: 500;
        background: rgba(0,200,255,0.05);
        border-bottom: 1px solid var(--border);
    }
    .data-table td {
        padding: 14px 16px;
        font-size: 13px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .data-table tr:hover td {
        background: rgba(0,200,255,0.04);
    }
    .session-id {
        font-family: 'DM Mono', monospace;
        font-weight: 600;
        color: var(--neon);
        font-size: 11px;
    }
    .pc-number {
        font-family: 'DM Mono', monospace;
        font-weight: 600;
        color: var(--text-hi);
    }
    .user-name {
        font-weight: 500;
        color: var(--text-hi);
    }
    .time-display {
        font-family: 'DM Mono', monospace;
        font-weight: 600;
    }
    .time-remaining {
        color: var(--accent-grn);
    }
    .time-warning {
        color: var(--accent-warn);
    }
    .time-critical {
        color: var(--accent-red);
    }
    .time-ended {
        color: var(--text-lo);
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-family: 'DM Mono', monospace;
        font-weight: 600;
    }
    .status-badge.active {
        background: rgba(0,230,118,0.15);
        color: var(--accent-grn);
    }
    .status-badge.ended {
        background: rgba(255,180,0,0.15);
        color: var(--accent-warn);
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .actions-cell {
        display: flex;
        gap: 8px;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s;
        border: 1px solid transparent;
        font-size: 14px;
    }
    .action-btn.end {
        background: rgba(255,59,107,0.1);
        color: var(--accent-red);
        border-color: rgba(255,59,107,0.2);
    }
    .action-btn.end:hover {
        background: rgba(255,59,107,0.2);
    }
    .action-btn.extend {
        background: rgba(255,180,0,0.1);
        color: var(--accent-warn);
        border-color: rgba(255,180,0,0.2);
    }
    .action-btn.extend:hover {
        background: rgba(255,180,0,0.2);
    }
    .action-btn.view {
        background: rgba(0,200,255,0.1);
        color: var(--neon);
        border-color: rgba(0,200,255,0.2);
    }
    .action-btn.view:hover {
        background: rgba(0,200,255,0.2);
    }

    /* Active Sessions Highlight */
    .active-sessions-section {
        margin-bottom: 24px;
    }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-hi);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title span {
        color: var(--neon);
    }

    /* Alerts */
    .alert {
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success {
        background: rgba(0,230,118,0.12);
        color: var(--accent-grn);
        border: 1px solid rgba(0,230,118,0.25);
    }
    .alert-error {
        background: rgba(255,59,107,0.12);
        color: var(--accent-red);
        border: 1px solid rgba(255,59,107,0.25);
    }

    /* Empty State */
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
        margin-bottom: 8px;
    }
    .empty-text {
        font-size: 13px;
        color: var(--text-mid);
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all .3s;
    }
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .modal {
        background: var(--bg-mid);
        border: 1px solid var(--border);
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        transform: scale(0.95);
        transition: transform .3s;
    }
    .modal-overlay.active .modal {
        transform: scale(1);
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }
    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-hi);
    }
    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-lo);
        font-size: 18px;
        transition: all .2s;
    }
    .modal-close:hover {
        background: var(--glass);
        color: var(--text-hi);
    }
    .modal-body {
        padding: 24px;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-mid);
        margin-bottom: 8px;
    }
    .form-input {
        width: 100%;
        padding: 12px 16px;
        background: var(--bg-deep);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-hi);
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        outline: none;
        transition: border-color .2s;
    }
    .form-input:focus {
        border-color: var(--neon);
    }
    .form-select {
        width: 100%;
        padding: 12px 16px;
        background: var(--bg-deep);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-hi);
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        outline: none;
        cursor: pointer;
    }
    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding: 20px 24px;
        border-top: 1px solid var(--border);
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .page-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">Session <span>Monitoring</span></div>
    <div style="display: flex; align-items: center; gap: 16px;">
        <div class="live-indicator">LIVE MONITORING</div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success">
        <span>✓</span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <span>✕</span> {{ session('error') }}
    </div>
@endif

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-label">TOTAL SESSIONS</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card active">
        <div class="stat-label">ACTIVE SESSIONS</div>
        <div class="stat-value">{{ $stats['active'] }}</div>
    </div>
    <div class="stat-card ended">
        <div class="stat-label">ENDED SESSIONS</div>
        <div class="stat-value">{{ $stats['ended'] }}</div>
    </div>
</div>

<!-- Active Sessions Section -->
@if($activeSessions->count() > 0)
<div class="active-sessions-section">
    <div class="section-header">
        <div class="section-title">
            <span>⚡</span> Active Users Currently Using PCs
        </div>
    </div>
    <div class="glass-card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>PC Unit</th>
                        <th>User</th>
                        <th>Start Time</th>
                        <th>Time Remaining</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeSessions as $session)
                    <tr>
                        <td>
                            <span class="session-id">{{ $session->session_id }}</span>
                        </td>
                        <td>
                            <span class="pc-number">{{ $session->pc_unit_number }}</span>
                        </td>
                        <td>
                            <span class="user-name">{{ $session->user_session_name }}</span>
                        </td>
                        <td>
                            {{ $session->start_time->format('M d, Y H:i') }}
                        </td>
                        <td>
                            @php
                                $remainingClass = 'time-remaining';
                                if (isset($session->current_remaining)) {
                                    if ($session->current_remaining <= 5) {
                                        $remainingClass = 'time-critical';
                                    } elseif ($session->current_remaining <= 15) {
                                        $remainingClass = 'time-warning';
                                    }
                                }
                            @endphp
                            <span class="time-display {{ $remainingClass }}">
                                @if(isset($session->current_remaining))
                                    {{ sprintf('%02d:%02d', floor($session->current_remaining / 60), $session->current_remaining % 60) }}
                                @else
                                    {{ $session->remaining_time_formatted }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn extend" title="Extend Time" onclick="openExtendModal('{{ $session->session_id }}')">
                                    +
                                </button>
                                <form method="POST" action="{{ route('sessions.end', $session->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to end this session?')">
                                    @csrf
                                    <button type="submit" class="action-btn end" title="End Session">
                                        ⏹
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- All Sessions -->
<div class="section-header">
    <div class="section-title">
        <span>📋</span> All Sessions
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('sessions.index') }}" class="filters-bar">
    <div class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Search session, PC, or user..." value="{{ request('search') }}">
    </div>
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Ended" {{ request('status') == 'Ended' ? 'selected' : '' }}>Ended</option>
    </select>
</form>

<!-- Data Table -->
<div class="glass-card">
    <div class="table-wrapper">
        @if($sessions->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>PC Unit</th>
                    <th>User</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                <tr>
                    <td>
                        <span class="session-id">{{ $session->session_id }}</span>
                    </td>
                    <td>
                        <span class="pc-number">{{ $session->pc_unit_number }}</span>
                    </td>
                    <td>
                        <span class="user-name">{{ $session->user_session_name }}</span>
                    </td>
                    <td>
                        {{ $session->start_time->format('M d, Y H:i') }}
                    </td>
                    <td>
                        {{ $session->end_time ? $session->end_time->format('M d, Y H:i') : '-' }}
                    </td>
                    <td>
                        <span class="time-display {{ $session->status === 'Active' ? 'time-remaining' : 'time-ended' }}">
                            {{ $session->duration }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ strtolower($session->status) }}">
                            <span class="status-dot"></span>
                            {{ $session->status }}
                        </span>
                    </td>
                    <td>
                        <div class="actions-cell">
                            @if($session->status === 'Active')
                            <form method="POST" action="{{ route('sessions.end', $session->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to end this session?')">
                                @csrf
                                <button type="submit" class="action-btn end" title="End Session">
                                    ⏹
                                </button>
                            </form>
                            @else
                            <span style="color: var(--text-lo); font-size: 11px;">Completed</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <div class="empty-title">No Sessions Found</div>
            <div class="empty-text">Sessions will appear here when users start using PC units.</div>
        </div>
        @endif
    </div>
</div>

<!-- Extend Time Modal -->
<div class="modal-overlay" id="extendModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Extend Session Time</div>
            <div class="modal-close" onclick="closeExtendModal()">×</div>
        </div>
        <form method="POST" id="extendForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Session ID</label>
                    <input type="text" id="extendSessionId" class="form-input" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Additional Time (minutes)</label>
                    <select name="additional_time" class="form-select" required>
                        <option value="15">15 minutes</option>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="120">2 hours</option>
                        <option value="240">4 hours</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="closeExtendModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Extend Time</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openExtendModal(sessionId) {
        const modal = document.getElementById('extendModal');
        const form = document.getElementById('extendForm');
        const sessionIdInput = document.getElementById('extendSessionId');
        
        sessionIdInput.value = sessionId;
        form.action = '/admin/sessions/' + sessionId + '/extend';
        
        modal.classList.add('active');
    }
    
    function closeExtendModal() {
        const modal = document.getElementById('extendModal');
        modal.classList.remove('active');
    }
    
    // Close modal when clicking outside
    document.getElementById('extendModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeExtendModal();
        }
    });
    
    // Auto-refresh every 30 seconds for live monitoring
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>
@endsection

