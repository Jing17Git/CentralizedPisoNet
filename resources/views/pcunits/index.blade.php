@extends('layouts.app')

@section('title', 'PC Units Monitor')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">PC Units Monitor</h1>
        <p class="page-subtitle">Real-time status, active sessions, and revenue per station</p>
    </div>
    <div style="display:flex; align-items:center; gap:12px;">
        <span id="lastUpdate" style="font-size:12px; color:var(--text-muted);">Updating...</span>
        <button class="btn btn-primary" onclick="fetchRealtimeStatus()" id="refreshBtn">
            Refresh
        </button>
    </div>
</div>

<div class="stat-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-label">Total Stations</div>
        <div class="stat-value" id="stat-total">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Online (Available)</div>
        <div class="stat-value" id="stat-online" style="color:var(--success);">{{ $stats['online'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Busy (In Use)</div>
        <div class="stat-value" id="stat-busy" style="color:var(--danger);">{{ $stats['busy'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Offline</div>
        <div class="stat-value" id="stat-offline" style="color:var(--text-muted);">{{ $stats['offline'] }}</div>
    </div>
</div>

<div id="machinesGrid" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
    @forelse($machines as $machine)
        @php
            $pcNumber = (int) str_replace('PC-', '', $machine->machine_code);
            $session = $sessions[$pcNumber] ?? null;
            $machineCoins = $todayCoins[$machine->id] ?? collect();
            
            $status = 'offline';
            $statusBadgeClass = 'badge-secondary';
            $statusLabel = 'OFFLINE';
            $statusColor = 'var(--text-muted)';
            
            if ($session) {
                $status = 'busy';
                $statusBadgeClass = 'badge-danger';
                $statusLabel = 'BUSY';
                $statusColor = 'var(--danger)';
            } elseif ($machine->status === 'online') {
                $status = 'online';
                $statusBadgeClass = 'badge-success';
                $statusLabel = 'ONLINE';
                $statusColor = 'var(--success)';
            }
        @endphp

        <div class="card machine-card" id="card-{{ $machine->machine_code }}" style="border-top: 3px solid {{ $statusColor }}; padding: 20px; display:flex; flex-direction:column; justify-content:space-between; gap:16px;">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 14px;">
                    <div>
                        <h2 style="font-size:18px; font-weight:700; color:var(--text); margin:0;">{{ $machine->machine_code }}</h2>
                        <span style="font-size:12px; color:var(--text-muted);">ID: #{{ $machine->id }}</span>
                    </div>
                    <span class="badge {{ $statusBadgeClass }}" id="badge-{{ $machine->machine_code }}">{{ $statusLabel }}</span>
                </div>

                <div style="background:var(--bg-deep); border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px; text-align:center; margin-bottom:14px;">
                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); margin-bottom:4px;">Remaining Time</div>
                    <div id="timer-{{ $machine->machine_code }}" style="font-size:24px; font-weight:700; font-family:monospace; color:{{ $session ? 'var(--accent)' : 'var(--text-muted)' }};">
                        @if($session && $session->remaining_time)
                            @php
                                $hours = floor($session->remaining_time / 60);
                                $minutes = $session->remaining_time % 60;
                            @endphp
                            {{ sprintf('%02d:%02d:00', $hours, $minutes) }}
                        @else
                            --:--:--
                        @endif
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap: 8px; text-align:center;">
                    <div style="background:var(--bg-deep); border:1px solid var(--border); border-radius:var(--radius-sm); padding:8px 4px;">
                        <div style="font-size:11px; color:var(--text-muted);">Coins</div>
                        <div style="font-size:14px; font-weight:700; color:var(--text);" id="coins-{{ $machine->machine_code }}">
                            {{ $machineCoins->sum('coins_inserted') }}
                        </div>
                    </div>
                    <div style="background:var(--bg-deep); border:1px solid var(--border); border-radius:var(--radius-sm); padding:8px 4px;">
                        <div style="font-size:11px; color:var(--text-muted);">Minutes</div>
                        <div style="font-size:14px; font-weight:700; color:var(--text);" id="minutes-{{ $machine->machine_code }}">
                            {{ $machineCoins->sum('minutes_purchased') }}
                        </div>
                    </div>
                    <div style="background:var(--bg-deep); border:1px solid var(--border); border-radius:var(--radius-sm); padding:8px 4px;">
                        <div style="font-size:11px; color:var(--text-muted);">Earned</div>
                        <div style="font-size:14px; font-weight:700; color:var(--accent);" id="earned-{{ $machine->machine_code }}">
                            ₱{{ number_format($machineCoins->sum('amount'), 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div style="border-top:1px solid var(--border); padding-top:10px; display:flex; justify-content:space-between; align-items:center; font-size:12px; color:var(--text-muted);">
                <span>Heartbeat:</span>
                <span id="heartbeat-{{ $machine->machine_code }}">{{ $machine->last_heartbeat ? $machine->last_heartbeat->diffForHumans() : 'Never' }}</span>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; padding: 48px 16px; text-align: center;">
            <p class="td-empty" style="margin: 0;">No PC units or machines found in the database.</p>
        </div>
    @endforelse
</div>

<script>
    let pollInterval = null;

    function formatTime(minutesTotal) {
        if (!minutesTotal || minutesTotal <= 0) return '--:--:--';
        const hours = Math.floor(minutesTotal / 60);
        const minutes = Math.floor(minutesTotal % 60);
        return String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':00';
    }

    function fetchRealtimeStatus() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) refreshBtn.disabled = true;

        fetch('{{ route("pc-units.realtime-status") }}')
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(json => {
                if (!json.success || !json.data) return;

                let online = 0, busy = 0, offline = 0;

                json.data.forEach(pc => {
                    const card = document.getElementById('card-' + pc.machine_code);
                    const badge = document.getElementById('badge-' + pc.machine_code);
                    const timer = document.getElementById('timer-' + pc.machine_code);
                    const coins = document.getElementById('coins-' + pc.machine_code);
                    const minutes = document.getElementById('minutes-' + pc.machine_code);
                    const earned = document.getElementById('earned-' + pc.machine_code);
                    const heartbeat = document.getElementById('heartbeat-' + pc.machine_code);

                    if (pc.status === 'busy') {
                        busy++;
                        if (card) card.style.borderTopColor = 'var(--danger)';
                        if (badge) {
                            badge.className = 'badge badge-danger';
                            badge.textContent = 'BUSY';
                        }
                        if (timer) {
                            timer.style.color = 'var(--accent)';
                            timer.textContent = formatTime(pc.remaining_time);
                        }
                    } else if (pc.status === 'online') {
                        online++;
                        if (card) card.style.borderTopColor = 'var(--success)';
                        if (badge) {
                            badge.className = 'badge badge-success';
                            badge.textContent = 'ONLINE';
                        }
                        if (timer) {
                            timer.style.color = 'var(--text-muted)';
                            timer.textContent = '--:--:--';
                        }
                    } else {
                        offline++;
                        if (card) card.style.borderTopColor = 'var(--text-muted)';
                        if (badge) {
                            badge.className = 'badge badge-secondary';
                            badge.textContent = 'OFFLINE';
                        }
                        if (timer) {
                            timer.style.color = 'var(--text-muted)';
                            timer.textContent = '--:--:--';
                        }
                    }

                    if (coins) coins.textContent = pc.coins_today ?? 0;
                    if (minutes) minutes.textContent = pc.minutes_today ?? 0;
                    if (earned) earned.textContent = '₱' + Number(pc.earned_today ?? 0).toFixed(2);
                    if (heartbeat) heartbeat.textContent = pc.last_heartbeat ?? 'Never';
                });

                const statTotal = document.getElementById('stat-total');
                const statOnline = document.getElementById('stat-online');
                const statBusy = document.getElementById('stat-busy');
                const statOffline = document.getElementById('stat-offline');
                const lastUpdate = document.getElementById('lastUpdate');

                if (statTotal) statTotal.textContent = json.data.length;
                if (statOnline) statOnline.textContent = online;
                if (statBusy) statBusy.textContent = busy;
                if (statOffline) statOffline.textContent = offline;
                if (lastUpdate) lastUpdate.textContent = 'Updated ' + new Date().toLocaleTimeString();
            })
            .catch(err => {
                console.error('Failed to fetch real-time status:', err);
                const lastUpdate = document.getElementById('lastUpdate');
                if (lastUpdate) lastUpdate.textContent = 'Update failed';
            })
            .finally(() => {
                if (refreshBtn) refreshBtn.disabled = false;
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchRealtimeStatus();
        pollInterval = setInterval(fetchRealtimeStatus, 5000);
    });

    window.addEventListener('beforeunload', () => {
        if (pollInterval) clearInterval(pollInterval);
    });
</script>
@endsection
