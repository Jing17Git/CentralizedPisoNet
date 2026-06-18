@extends('layouts.app')
@section('title', 'Sessions')
@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="value" id="totalSessions">{{ $stats['total'] }}</div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value" id="activeSessions">{{ $stats['active'] }}</div><div class="label">Active</div></div>
    <div class="stat-card"><div class="value" id="endedSessions">{{ $stats['ended'] }}</div><div class="label">Ended</div></div>
</div>

<div class="card">
    <h3>Active Sessions <span style="font-size:12px;color:#666;" id="lastUpdate"></span></h3>
    <table>
        <thead><tr><th>Session ID</th><th>PC Unit</th><th>User</th><th>Start Time</th><th>Remaining</th><th>Action</th></tr></thead>
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
                        <button type="submit" class="btn btn-danger" style="padding:4px 10px;font-size:12px;">End</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#666;">No active sessions.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Session History</h3>
    <table>
        <thead><tr><th>Session ID</th><th>PC Unit</th><th>User</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
        <tbody id="sessionHistoryTable">
        @forelse($sessions as $session)
            <tr>
                <td>{{ $session->session_id }}</td>
                <td>{{ $session->pc_unit_number }}</td>
                <td>{{ $session->user_session_name }}</td>
                <td>{{ $session->start_time->format('Y-m-d H:i') }}</td>
                <td>{{ $session->end_time ? $session->end_time->format('Y-m-d H:i') : '-' }}</td>
                <td><span class="badge {{ $session->status === 'Active' ? 'badge-success' : 'badge-info' }}">{{ $session->status }}</span></td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#666;">No sessions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateSessions() {
    fetch('{{ route("sessions.index") }}')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const total = doc.querySelector('#totalSessions');
            if (total) document.getElementById('totalSessions').innerHTML = total.innerHTML;
            
            const active = doc.querySelector('#activeSessions');
            if (active) document.getElementById('activeSessions').innerHTML = active.innerHTML;
            
            const ended = doc.querySelector('#endedSessions');
            if (ended) document.getElementById('endedSessions').innerHTML = ended.innerHTML;
            
            const activeTable = doc.querySelector('#activeSessionsTable');
            if (activeTable) document.getElementById('activeSessionsTable').innerHTML = activeTable.innerHTML;
            
            const historyTable = doc.querySelector('#sessionHistoryTable');
            if (historyTable) document.getElementById('sessionHistoryTable').innerHTML = historyTable.innerHTML;
            
            const now = new Date();
            document.getElementById('lastUpdate').textContent = '(Updated: ' + now.toLocaleTimeString() + ')';
        })
        .catch(err => console.error('Update failed:', err));
}

updateInterval = setInterval(updateSessions, 3000);

window.addEventListener('beforeunload', () => {
    if (updateInterval) clearInterval(updateInterval);
});
</script>
@endsection
