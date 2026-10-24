@extends('layouts.app')
@section('title', 'PC Units')
@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="value">{{ $stats['total'] }}</div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value">{{ $stats['active'] }}</div><div class="label">Active</div></div>
    <div class="stat-card"><div class="value">{{ $stats['available'] }}</div><div class="label">Available</div></div>
    <div class="stat-card"><div class="value">{{ $stats['in_use'] }}</div><div class="label">In Use</div></div>
    <div class="stat-card"><div class="value">{{ $stats['offline'] }}</div><div class="label">Offline</div></div>
</div>

<div class="card">
    <h3>Add PC Unit</h3>
    <form method="POST" action="{{ route('pc-units.store') }}" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end;">
        @csrf
        <div><label>PC Number</label><input type="text" name="pc_number" placeholder="e.g. PC-01" required></div>
        <div><label>IP Address</label><input type="text" name="ip_address" placeholder="e.g. 192.168.1.101" required></div>
        <div><label>Status</label>
            <select name="status">
                <option value="available">Available</option>
                <option value="offline">Offline</option>
            </select>
        </div>
        <div><button type="submit" class="btn btn-primary">Add</button></div>
    </form>
</div>

<div class="card">
    <h3>PC Units</h3>
    <table>
        <thead><tr><th>PC Number</th><th>IP Address</th><th>Status</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($pcUnits as $pc)
            <tr>
                <td>{{ $pc->pc_number }}</td>
                <td>{{ $pc->ip_address }}</td>
                <td><span class="badge {{ $pc->status === 'available' ? 'badge-success' : ($pc->status === 'in_use' ? 'badge-warning' : 'badge-danger') }}">{{ ucfirst(str_replace('_',' ',$pc->status)) }}</span></td>
                <td><span class="badge {{ $pc->is_active ? 'badge-success' : 'badge-danger' }}">{{ $pc->is_active ? 'Yes' : 'No' }}</span></td>
                <td>
                    <form method="POST" action="{{ route('pc-units.destroy', $pc) }}" style="display:inline;" onsubmit="return confirm('Delete this PC unit?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:4px 10px;font-size:12px;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;color:#666;">No PC units yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
