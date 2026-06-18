@extends('layouts.app')
@section('title', 'PC Units Monitor')
@section('content')

<style>
.pc-status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.pc-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.pc-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--status-color);
}

.pc-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 200, 255, 0.2);
}

.pc-card.status-busy {
    --status-color: #e74c3c;
    border-color: rgba(231, 76, 60, 0.3);
}

.pc-card.status-online {
    --status-color: #2ecc71;
    border-color: rgba(46, 204, 113, 0.3);
}

.pc-card.status-offline {
    --status-color: #95a5a6;
    border-color: rgba(149, 165, 166, 0.3);
}

.pc-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.pc-number {
    font-size: 32px;
    font-weight: bold;
    color: #00c8ff;
    text-shadow: 0 0 20px rgba(0, 200, 255, 0.5);
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    animation: pulse 2s ease-in-out infinite;
}

.status-badge.busy {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: #fff;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
}

.status-badge.online {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: #fff;
    box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
}

.status-badge.offline {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
    color: #fff;
    box-shadow: 0 4px 15px rgba(149, 165, 166, 0.4);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.timer-display {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin: 20px 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.timer-display.active {
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(192, 57, 43, 0.2));
    border-color: rgba(231, 76, 60, 0.5);
}

.timer-label {
    font-size: 11px;
    color: #aaa;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.timer-value {
    font-size: 42px;
    font-weight: bold;
    color: #00c8ff;
    font-family: 'Courier New', monospace;
    text-shadow: 0 0 10px rgba(0, 200, 255, 0.5);
}

.timer-value.active {
    color: #e74c3c;
    text-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin: 20px 0;
}

.stat-box {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.stat-value {
    font-size: 24px;
    font-weight: bold;
    color: #00c8ff;
}

.stat-label {
    font-size: 10px;
    color: #888;
    margin-top: 4px;
    text-transform: uppercase;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 12px;
}

.info-label {
    color: #888;
}

.info-value {
    color: #fff;
    font-weight: 500;
}

.stats-header {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 30px;
}

.stat-card-mini {
    background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border-left: 4px solid var(--card-color);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.stat-card-mini.total { --card-color: #3498db; }
.stat-card-mini.online { --card-color: #2ecc71; }
.stat-card-mini.busy { --card-color: #e74c3c; }
.stat-card-mini.offline { --card-color: #95a5a6; }

.stat-card-mini .value {
    font-size: 36px;
    font-weight: bold;
    color: var(--card-color);
    margin-bottom: 8px;
}

.stat-card-mini .label {
    font-size: 12px;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.update-time {
    text-align: center;
    color: #888;
    font-size: 11px;
    margin-top: 20px;
}
</style>

{{-- Stats Header --}}
<div class="stats-header">
    <div class="stat-card-mini total">
        <div class="value" id="total-count">{{ $stats['total'] }}</div>
        <div class="label">Total PCs</div>
    </div>
    <div class="stat-card-mini online">
        <div class="value" id="online-count">{{ $stats['online'] }}</div>
        <div class="label">🟢 Online</div>
    </div>
    <div class="stat-card-mini busy">
        <div class="value" id="busy-count">{{ $stats['busy'] }}</div>
        <div class="label">🔴 Busy</div>
    </div>
    <div class="stat-card-mini offline">
        <div class="value" id="offline-count">{{ $stats['offline'] }}</div>
        <div class="label">⚫ Offline</div>
    </div>
</div>

{{-- PC Units Grid --}}
<div class="pc-status-grid">
@forelse($machines as $machine)
    @php
        $pcNumber = (int) str_replace('PC-', '', $machine->machine_code);
        $session = $sessions[$pcNumber] ?? null;
        $history = $histories[$pcNumber] ?? collect();
        $todayCoins = $todayCoins[$machine->id] ?? collect();
        
        // Determine status
        if ($session) {
            $status = 'busy';
            $statusText = 'In Use';
            $statusIcon = '🔴';
        } elseif ($machine->status === 'online') {
            $status = 'online';
            $statusText = 'Available';
            $statusIcon = '🟢';
        } else {
            $status = 'offline';
            $statusText = 'Offline';
            $statusIcon = '⚫';
        }
    @endphp
    
    <div class="pc-card status-{{ $status }}" data-machine="{{ $machine->machine_code }}">
        {{-- Header --}}
        <div class="pc-header">
            <div class="pc-number">{{ $machine->machine_code }}</div>
            <div class="status-badge {{ $status }}">
                {{ $statusIcon }} {{ $statusText }}
            </div>
        </div>

        {{-- Timer Display --}}
        <div class="timer-display {{ $session ? 'active' : '' }}">
            <div class="timer-label">{{ $session ? 'Time Remaining' : 'No Active Session' }}</div>
            <div class="timer-value {{ $session ? 'active' : '' }}" data-timer="{{ $machine->machine_code }}">
                @if($session)
                    {{ sprintf('%02d:%02d:%02d', floor($session->remaining_time / 60), $session->remaining_time % 60, 0) }}
                @else
                    --:--:--
                @endif
            </div>
            @if($session)
            <div class="timer-label" style="margin-top: 8px;">
                Started {{ $session->start_time?->format('h:i A') }}
            </div>
            @endif
        </div>

        {{-- Today's Stats --}}
        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-value" data-coins="{{ $machine->machine_code }}">{{ $todayCoins->sum('coins_inserted') }}</div>
                <div class="stat-label">Coins</div>
            </div>
            <div class="stat-box">
                <div class="stat-value" data-minutes="{{ $machine->machine_code }}">{{ $todayCoins->sum('minutes_purchased') }}</div>
                <div class="stat-label">Minutes</div>
            </div>
            <div class="stat-box">
                <div class="stat-value" style="color: #2ecc71;" data-earned="{{ $machine->machine_code }}">₱{{ number_format($todayCoins->sum('amount'), 0) }}</div>
                <div class="stat-label">Earned</div>
            </div>
        </div>

        {{-- Info --}}
        <div style="margin-top: 16px;">
            <div class="info-row">
                <span class="info-label">Last Heartbeat</span>
                <span class="info-value" data-heartbeat="{{ $machine->machine_code }}">
                    {{ $machine->last_heartbeat ? $machine->last_heartbeat->diffForHumans() : 'Never' }}
                </span>
            </div>
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Slot Status</span>
                <span class="info-value">
                    {{ $machine->slot_enabled ? '🔓 Open' : '🔒 Closed' }}
                </span>
            </div>
        </div>
    </div>
@empty
    <div class="card" style="text-align:center;color:#555;padding:40px;">
        <p style="font-size:18px;margin-bottom:10px;">⚠️ No machines found</p>
        <p style="font-size:13px;color:#777;">Machines will appear here when client apps connect</p>
    </div>
@endforelse
</div>

<div class="update-time">
    Last updated: <span id="update-timestamp">{{ now()->format('H:i:s') }}</span> · Auto-refresh every 3 seconds
</div>

<script>
// Real-time updates every 3 seconds
setInterval(async () => {
    try {
        const response = await fetch('/pc-units/realtime-status');
        const data = await response.json();
        
        if (data.success) {
            // Update stats header
            let onlineCount = 0, busyCount = 0, offlineCount = 0;
            
            data.data.forEach(pc => {
                const card = document.querySelector(`[data-machine="${pc.machine_code}"]`);
                if (!card) return;
                
                // Update status badge and card class
                const badge = card.querySelector('.status-badge');
                let statusIcon, statusText, statusClass;
                
                if (pc.status === 'busy') {
                    statusIcon = '🔴';
                    statusText = 'In Use';
                    statusClass = 'busy';
                    busyCount++;
                } else if (pc.status === 'online') {
                    statusIcon = '🟢';
                    statusText = 'Available';
                    statusClass = 'online';
                    onlineCount++;
                } else {
                    statusIcon = '⚫';
                    statusText = 'Offline';
                    statusClass = 'offline';
                    offlineCount++;
                }
                
                card.className = `pc-card status-${statusClass}`;
                badge.className = `status-badge ${statusClass}`;
                badge.textContent = `${statusIcon} ${statusText}`;
                
                // Update timer
                const timerEl = card.querySelector(`[data-timer="${pc.machine_code}"]`);
                const timerDisplay = card.querySelector('.timer-display');
                if (pc.remaining_time) {
                    const hours = Math.floor(pc.remaining_time / 60);
                    const minutes = pc.remaining_time % 60;
                    timerEl.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;
                    timerEl.classList.add('active');
                    timerDisplay.classList.add('active');
                } else {
                    timerEl.textContent = '--:--:--';
                    timerEl.classList.remove('active');
                    timerDisplay.classList.remove('active');
                }
                
                // Update today's stats
                const coinsEl = card.querySelector(`[data-coins="${pc.machine_code}"]`);
                const minutesEl = card.querySelector(`[data-minutes="${pc.machine_code}"]`);
                const earnedEl = card.querySelector(`[data-earned="${pc.machine_code}"]`);
                
                if (coinsEl) coinsEl.textContent = pc.coins_today;
                if (minutesEl) minutesEl.textContent = pc.minutes_today;
                if (earnedEl) earnedEl.textContent = `₱${pc.earned_today}`;
                
                // Update heartbeat
                const heartbeatEl = card.querySelector(`[data-heartbeat="${pc.machine_code}"]`);
                if (heartbeatEl) heartbeatEl.textContent = pc.last_heartbeat || 'Never';
            });
            
            // Update header stats
            document.getElementById('online-count').textContent = onlineCount;
            document.getElementById('busy-count').textContent = busyCount;
            document.getElementById('offline-count').textContent = offlineCount;
            
            // Update timestamp
            const now = new Date();
            document.getElementById('update-timestamp').textContent = 
                now.toTimeString().split(' ')[0];
        }
    } catch (error) {
        console.error('Failed to update PC status:', error);
    }
}, 3000);
</script>

@endsection
