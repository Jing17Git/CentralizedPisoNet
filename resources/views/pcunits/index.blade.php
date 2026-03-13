@extends('layouts.admin')

@section('title', 'PC Units Management')

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
        grid-template-columns: repeat(5, 1fr);
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
    .stat-card.available .stat-value { color: #00e676; }
    .stat-card.in-use .stat-value { color: var(--accent-warn); }
    .stat-card.offline .stat-value { color: var(--accent-red); }

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
    .pc-number {
        font-family: 'DM Mono', monospace;
        font-weight: 600;
        color: var(--neon);
    }
    .ip-address {
        font-family: 'DM Mono', monospace;
        color: var(--text-mid);
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
    .status-badge.available {
        background: rgba(0,230,118,0.15);
        color: #00e676;
    }
    .status-badge.in_use {
        background: rgba(255,180,0,0.15);
        color: var(--accent-warn);
    }
    .status-badge.offline {
        background: rgba(255,59,107,0.15);
        color: var(--accent-red);
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .active-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-family: 'DM Mono', monospace;
        font-weight: 600;
    }
    .active-badge.active {
        background: rgba(0,230,118,0.15);
        color: var(--accent-grn);
    }
    .active-badge.inactive {
        background: rgba(255,255,255,0.08);
        color: var(--text-lo);
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
    .action-btn.edit {
        background: rgba(0,200,255,0.1);
        color: var(--neon);
        border-color: rgba(0,200,255,0.2);
    }
    .action-btn.edit:hover {
        background: rgba(0,200,255,0.2);
    }
    .action-btn.delete {
        background: rgba(255,59,107,0.1);
        color: var(--accent-red);
        border-color: rgba(255,59,107,0.2);
    }
    .action-btn.delete:hover {
        background: rgba(255,59,107,0.2);
    }
    .action-btn.toggle {
        background: rgba(255,180,0,0.1);
        color: var(--accent-warn);
        border-color: rgba(255,180,0,0.2);
    }
    .action-btn.toggle:hover {
        background: rgba(255,180,0,0.2);
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
        max-width: 480px;
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

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
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
    <div class="page-title">PC Units <span>Management</span></div>
    <button class="btn btn-primary" onclick="openModal('add')">
        <span>+</span> Add PC Unit
    </button>
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
        <div class="stat-label">TOTAL PC UNITS</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card active">
        <div class="stat-label">ACTIVE</div>
        <div class="stat-value">{{ $stats['active'] }}</div>
    </div>
    <div class="stat-card available">
        <div class="stat-label">AVAILABLE</div>
        <div class="stat-value">{{ $stats['available'] }}</div>
    </div>
    <div class="stat-card in-use">
        <div class="stat-label">IN USE</div>
        <div class="stat-value">{{ $stats['in_use'] }}</div>
    </div>
    <div class="stat-card offline">
        <div class="stat-label">OFFLINE</div>
        <div class="stat-value">{{ $stats['offline'] }}</div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('pcunits.index') }}" class="filters-bar">
    <div class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Search PC number or IP..." value="{{ request('search') }}">
    </div>
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
        <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
        <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
    </select>
    <select name="is_active" class="filter-select" onchange="this.form.submit()">
        <option value="">All Active Status</option>
        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
    </select>
</form>

<!-- Data Table -->
<div class="glass-card">
    <div class="table-wrapper">
        @if($pcUnits->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>PC Number</th>
                    <th>Branch ID</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Active</th>
                    <th>Last Activity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pcUnits as $pc)
                <tr>
                    <td>
                        <span class="pc-number">{{ $pc->pc_number }}</span>
                    </td>
                    <td>{{ $pc->branch_id }}</td>
                    <td>
                        <span class="ip-address">{{ $pc->ip_address ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="status-badge {{ $pc->status }}">
                            <span class="status-dot"></span>
                            {{ ucfirst(str_replace('_', ' ', $pc->status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="active-badge {{ $pc->is_active ? 'active' : 'inactive' }}">
                            {{ $pc->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        {{ $pc->last_activity ? $pc->last_activity->format('M d, Y H:i') : 'Never' }}
                    </td>
                    <td>
                        <div class="actions-cell">
                            <button class="action-btn edit" onclick="openModal('edit', {{ $pc->id }}, '{{ $pc->pc_number }}', '{{ $pc->ip_address }}', '{{ $pc->status }}', {{ $pc->branch_id }})" title="Edit">
                                ✎
                            </button>
                            <form method="POST" action="{{ route('pcunits.toggleActive', $pc->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn toggle" title="{{ $pc->is_active ? 'Deactivate' : 'Activate' }}">
                                    {{ $pc->is_active ? '◉' : '○' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('pcunits.destroy', $pc->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this PC unit?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <div class="empty-icon">🖥</div>
            <div class="empty-title">No PC Units Found</div>
            <div class="empty-text">Add your first PC unit to get started.</div>
        </div>
        @endif
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="pcModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="modalTitle">Add PC Unit</div>
            <div class="modal-close" onclick="closeModal()">×</div>
        </div>
        <form method="POST" id="pcForm">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="form-group">
                    <label class="form-label">PC Number</label>
                    <input type="text" name="pc_number" id="pcNumber" class="form-input" placeholder="e.g., PC-01" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Branch ID</label>
                    <input type="number" name="branch_id" id="branchId" class="form-input" value="1" min="1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">IP Address</label>
                    <input type="text" name="ip_address" id="ipAddress" class="form-input" placeholder="e.g., 192.168.1.101">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="pcStatus" class="form-select">
                        <option value="available">Available</option>
                        <option value="in_use">In Use</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Add PC Unit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(mode, id = null, pcNumber = '', ipAddress = '', status = 'available', branchId = 1) {
        const modal = document.getElementById('pcModal');
        const form = document.getElementById('pcForm');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const methodInput = document.getElementById('formMethod');
        
        document.getElementById('pcNumber').value = pcNumber;
        document.getElementById('ipAddress').value = ipAddress || '';
        document.getElementById('pcStatus').value = status;
        document.getElementById('branchId').value = branchId;
        
        if (mode === 'edit') {
            title.textContent = 'Edit PC Unit';
            submitBtn.textContent = 'Update PC Unit';
            methodInput.value = 'PUT';
            form.action = '/admin/pc-units/' + id;
        } else {
            title.textContent = 'Add PC Unit';
            submitBtn.textContent = 'Add PC Unit';
            methodInput.value = 'POST';
            form.action = '{{ route("pcunits.store") }}';
        }
        
        modal.classList.add('active');
    }
    
    function closeModal() {
        const modal = document.getElementById('pcModal');
        modal.classList.remove('active');
    }
    
    // Close modal when clicking outside
    document.getElementById('pcModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection

