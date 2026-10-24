@extends('layouts.app')
@section('title', 'Sessions')
@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value" id="totalSessions">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Sessions</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="activeSessions">{{ $stats['active'] }}</div>
        <div class="stat-label">Active Now</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="endedSessions">{{ $stats['ended'] }}</div>
        <div class="stat-label">Ended</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>◷ Active Sessions <span class="update-ts" id="lastUpdate"></span></h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Session ID</th>
                <th>PC Unit</th>
                <th>User</th>
                <th>Start Time</th>
                <th>Remaining</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="activeSessionsTable">
        @forelse($activeSessions as $session)
            <tr>
                <td>{{ $session->session_id }}</td>
                <td>{{ $session->pc_unit_number }}</td>
                <td>{{ $session->user_session_name }}</td>
                <td>{{ $session->start_time->format('H:i:s') }}</td>
                <td>{{ $session->current_remaining ?? $session->remaining_time }} min</td>
                <td>
                    <form method="POST" action="{{ route('sessions.destroy', $session) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:4px 10px;font-size:11px;">End</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="td-empty">No active sessions.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h3>◈ Session History</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Session ID</th>
                <th>PC Unit</th>
                <th>User</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="sessionHistoryTable">
        @forelse($sessions as $session)
            <tr>
                <td>{{ $session->session_id }}</td>
                <td>{{ $session->pc_unit_number }}</td>
                <td>{{ $session->user_session_name }}</td>
                <td>{{ $session->start_time->format('Y-m-d H:i') }}</td>
                <td>{{ $session->end_time ? $session->end_time->format('Y-m-d H:i') : '—' }}</td>
                <td><span class="badge {{ $session->status === 'Active' ? 'badge-success' : 'badge-info' }}">{{ $session->status }}</span></td>
            </tr>
        @empty
            <tr><td colspan="6" class="td-empty">No sessions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateSessions() {
    fetch('{{ route("sessions.index") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            ['totalSessions','activeSessions','endedSessions',
             'activeSessionsTable','sessionHistoryTable'].forEach(id => {
                const src = doc.getElementById(id);
                const dst = document.getElementById(id);
                if (src && dst) dst.innerHTML = src.innerHTML;
            });
            const el = document.getElementById('lastUpdate');
            if (el) el.textContent = 'Updated ' + new Date().toLocaleTimeString();
        })
        .catch(err => console.error('Sessions update failed:', err));
}

updateInterval = setInterval(updateSessions, 5000);
window.addEventListener('beforeunload', () => { if (updateInterval) clearInterval(updateInterval); });
</script>
@endsection
