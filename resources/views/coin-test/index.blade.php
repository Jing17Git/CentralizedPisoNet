@extends('layouts.app')
@section('title', 'Coin Test')
@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Left: Monitor --}}
    <div class="card" style="text-align:center;">
        <div class="card-header" style="text-align:left;">
            <h3>▷ Coin Slot Monitor</h3>
        </div>

        <div style="margin-bottom:16px;text-align:left;">
            <label for="machineSelect">Select Machine</label>
            <select id="machineSelect" onchange="resetMonitor()">
                @foreach($machines as $machine)
                    <option value="{{ $machine->machine_code }}" {{ request('machine')==$machine->machine_code?'selected':'' }}>
                        {{ $machine->machine_name }} ({{ $machine->machine_code }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Session Timer --}}
        <div style="background:var(--bg-deep);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:12px;">
            <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Session Remaining</div>
            <div id="timeDisplay" style="font-size:52px;font-weight:700;color:var(--accent);letter-spacing:4px;font-variant-numeric:tabular-nums;transition:color .4s;">--:--</div>
            <div id="sessionId" style="font-size:11px;color:var(--text-muted);margin-top:8px;">No active session</div>
            <div id="startTime" style="font-size:11px;color:var(--text-muted);"></div>
        </div>

        {{-- Pending Coins Box --}}
        <div id="pendingBox" style="background:rgba(63,185,80,.08);border:1px solid rgba(63,185,80,.3);border-radius:var(--radius);padding:16px;margin-bottom:12px;display:none;">
            <div style="font-size:11px;color:var(--text-muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">⏳ Coins Waiting — Click Done to confirm</div>
            <div id="pendingCoins"   style="font-size:26px;font-weight:700;color:var(--success);"></div>
            <div id="pendingMinutes" style="font-size:13px;color:var(--success);font-weight:600;margin-top:4px;"></div>
            <div id="pendingAmount"  style="font-size:12px;color:var(--text-muted);margin-top:2px;"></div>
            <div style="display:flex;gap:10px;margin-top:14px;justify-content:center;">
                <button class="btn btn-success" onclick="donePending()" style="flex:1;justify-content:center;" id="done-pending-btn">✓ Done</button>
                <button class="btn btn-danger"  onclick="cancelPending()" style="flex:1;justify-content:center;" id="cancel-pending-btn">✕ Cancel</button>
            </div>
        </div>

        {{-- Coin Slot State --}}
        <div id="slotStateBox" style="display:flex;align-items:center;justify-content:center;gap:10px;padding:10px;border-radius:var(--radius);background:var(--bg-deep);border:1px solid var(--border);margin-bottom:8px;">
            <div id="slotDot" style="width:10px;height:10px;border-radius:50%;background:var(--text-muted);flex-shrink:0;transition:background .3s;"></div>
            <span id="slotLabel" style="font-size:12px;color:var(--text-muted);font-weight:600;">Coin Slot: UNKNOWN</span>
        </div>

        {{-- Status --}}
        <div id="statusBox" style="padding:10px 14px;border-radius:var(--radius);background:var(--bg-deep);border:1px solid var(--border);color:var(--text-muted);font-size:12px;min-height:38px;text-align:center;">
            Waiting for coin...
        </div>

        <div style="margin-top:14px;display:flex;gap:8px;justify-content:center;">
            <button class="btn btn-success" onclick="startPolling()" id="start-poll-btn">▶ Start</button>
            <button class="btn btn-danger"  onclick="stopPolling()" id="stop-poll-btn">■ Stop</button>
            <button class="btn btn-outline" id="resetBtn" onclick="resetSession()" style="display:none;">↺ Reset</button>
        </div>
    </div>

    {{-- Right: Rates + History --}}
    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3>⬡ Current Rates</h3>
                <a href="{{ route('rates.index') }}" class="btn btn-outline" style="font-size:11px;">Edit Rates</a>
            </div>
            <table>
                <thead><tr><th>Coin</th><th>Minutes Given</th></tr></thead>
                <tbody id="ratesBody"><tr><td colspan="2" class="td-empty">Loading...</td></tr></tbody>
            </table>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>◈ Today's Coin History</h3>
            </div>
            <div style="max-height:260px;overflow-y:auto;">
                <table>
                    <thead><tr><th>Time</th><th>Coin</th><th>Minutes</th><th>Amount</th></tr></thead>
                    <tbody id="coinLogBody"><tr><td colspan="4" class="td-empty">No coins today</td></tr></tbody>
                </table>
            </div>
            <div id="todaySummary" style="margin-top:10px;font-size:11px;color:var(--text-muted);text-align:right;"></div>
        </div>
    </div>
</div>

<script>
var pollInterval=null, lastRemaining=null, lastSessionId=null, lastCoinCount=0, activeSession=null;
var csrf='{{ csrf_token() }}';

function machine(){ return document.getElementById('machineSelect').value; }
function setStatus(m,c){
    var b=document.getElementById('statusBox');
    b.style.color = c || 'var(--text-muted)';
    b.textContent = m;
}
function fmt(min){ return String(Math.floor(min/60)).padStart(2,'0')+':'+String(min%60).padStart(2,'0'); }
function flash(c){
    var d=document.getElementById('timeDisplay');
    d.style.color=c;
    setTimeout(function(){ d.style.color='var(--accent)'; }, 1500);
}

function updateRates(r){
    if(!r) return;
    document.getElementById('ratesBody').innerHTML=Object.entries(r).map(function(e){
        return '<tr><td>₱'+e[0]+'</td><td style="color:var(--success);font-weight:600;">'+e[1]+' minutes</td></tr>';
    }).join('');
}

function updateHistory(coins){
    var tbody=document.getElementById('coinLogBody');
    if(!coins||coins.length===0){
        tbody.innerHTML='<tr><td colspan="4" class="td-empty">No coins today</td></tr>';
        document.getElementById('todaySummary').textContent='';
        lastCoinCount=0; return;
    }
    if(lastCoinCount>0 && coins.length>lastCoinCount){
        var l=coins[0];
        flash('var(--success)');
        setStatus('✓ Done! +'+l.minutes_purchased+' minutes added','var(--success)');
    }
    lastCoinCount=coins.length;
    tbody.innerHTML=coins.map(function(c){
        var t=new Date(c.created_at).toLocaleTimeString();
        return '<tr><td>'+t+'</td><td>₱'+parseFloat(c.amount).toFixed(0)+'</td><td style="color:var(--success);font-weight:600;">+'+c.minutes_purchased+' min</td><td>₱'+parseFloat(c.amount).toFixed(2)+'</td></tr>';
    }).join('');
    var tm=coins.reduce(function(s,c){return s+c.minutes_purchased;},0);
    var ta=coins.reduce(function(s,c){return s+parseFloat(c.amount);},0);
    document.getElementById('todaySummary').textContent=coins.length+' transaction(s) · '+tm+' min · ₱'+ta.toFixed(2);
}

function updatePending(pending){
    var box=document.getElementById('pendingBox');
    if(pending && pending.coins>0){
        box.style.display='block';
        document.getElementById('pendingCoins').textContent=pending.coins+' coin(s) inserted';
        document.getElementById('pendingMinutes').textContent='+'+pending.minutes+' minutes will be added';
        document.getElementById('pendingAmount').textContent='Total: ₱'+parseFloat(pending.amount).toFixed(2);
        setStatus('⬡ Coins waiting — click Done to confirm!','var(--warning)');
    } else {
        box.style.display='none';
    }
}

function updateSlotState(open){
    var dot=document.getElementById('slotDot');
    var lbl=document.getElementById('slotLabel');
    if(open){
        dot.style.background='var(--success)';
        lbl.style.color='var(--success)';
        lbl.textContent='Coin Slot: OPEN — accepting coins';
    } else {
        dot.style.background='var(--danger)';
        lbl.style.color='var(--danger)';
        lbl.textContent='Coin Slot: CLOSED — returning coins';
    }
}

function poll(){
    fetch('/coin-test/session/'+machine()).then(function(r){
        if(!r.ok) throw new Error('HTTP '+r.status);
        return r.json();
    }).then(function(data){
        updateRates(data.rates);
        updateHistory(data.today_coins);
        updatePending(data.pending);
        updateSlotState(data.slot_enabled);

        if(data.success){
            var rem=parseInt(data.remaining_time);
            activeSession=data.session_id;
            document.getElementById('timeDisplay').textContent=fmt(rem);
            document.getElementById('sessionId').textContent='Session: '+data.session_id;
            document.getElementById('startTime').textContent=data.start_time?'Started: '+data.start_time:'';
            document.getElementById('resetBtn').style.display='inline-flex';
            if(!(data.pending && data.pending.coins>0)){
                if(lastSessionId!==data.session_id){lastSessionId=data.session_id;lastRemaining=rem;setStatus('✓ Active — '+fmt(rem)+' remaining','var(--success)');}
                else if(lastRemaining!==null && rem>lastRemaining){var a=rem-lastRemaining;setStatus('✓ +'+a+' minutes added! Now: '+fmt(rem),'var(--success)');flash('var(--success)');}
            }
            lastRemaining=rem;
            lastSessionId=data.session_id;
        } else {
            activeSession=null;
            document.getElementById('timeDisplay').textContent='--:--';
            document.getElementById('sessionId').textContent='No active session';
            document.getElementById('startTime').textContent='';
            document.getElementById('resetBtn').style.display='none';
            if(lastSessionId!==null){setStatus('Session ended. Insert coins to start.','var(--warning)');lastSessionId=null;lastRemaining=null;}
            else if(!data.pending||data.pending.coins===0){ setStatus('Waiting for coin...','var(--text-muted)'); }
        }
    }).catch(function(e){ setStatus('Error: '+e.message,'var(--danger)'); });
}

function donePending(){
    fetch('/coin-test/done/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){
            document.getElementById('pendingBox').style.display='none';
            setStatus('\u2713 Done! +'+d.minutes+' minutes added','var(--success)');
            flash('var(--success)');
            document.getElementById('timeDisplay').textContent=fmt(d.remaining_time);
            lastRemaining=d.remaining_time;
            lastSessionId=null;
        } else { setStatus('Error: '+(d.message||'failed'),'var(--danger)'); }
    }).catch(function(e){ setStatus('Error: '+e.message,'var(--danger)'); });
}

function cancelPending(){
    fetch('/coin-test/cancel/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}})
    .then(function(r){return r.json();}).then(function(){
        document.getElementById('pendingBox').style.display='none';
        setStatus('Cancelled. Waiting for coin...','var(--text-muted)');
    }).catch(function(e){ setStatus('Error: '+e.message,'var(--danger)'); });
}

function resetSession(){
    if(!activeSession) return;
    if(!confirm('Reset session? This will end the current session.')) return;
    fetch('/coin-test/reset/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){
            lastSessionId=null;lastRemaining=null;activeSession=null;
            document.getElementById('timeDisplay').textContent='--:--';
            document.getElementById('sessionId').textContent='No active session';
            document.getElementById('resetBtn').style.display='none';
            setStatus('Session reset.','var(--success)');
        } else { setStatus('Reset failed: '+(d.message||'error'),'var(--danger)'); }
    }).catch(function(e){ setStatus('Error: '+e.message,'var(--danger)'); });
}

function resetMonitor(){
    lastRemaining=null;lastSessionId=null;lastCoinCount=0;activeSession=null;
    document.getElementById('timeDisplay').textContent='--:--';
    document.getElementById('sessionId').textContent='No active session';
    document.getElementById('startTime').textContent='';
    document.getElementById('pendingBox').style.display='none';
    document.getElementById('resetBtn').style.display='none';
    setStatus('Waiting for coin...','var(--text-muted)');
    if(pollInterval){stopPolling();startPolling();}
}

function startPolling(){
    fetch('/coin-test/open-slot/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}});
    updateSlotState(true);
    if(pollInterval) return;
    poll();
    pollInterval=setInterval(poll,2000);
    setStatus('Monitoring — insert a coin!','var(--success)');
}

function stopPolling(){
    clearInterval(pollInterval);
    pollInterval=null;
    fetch('/coin-test/close-slot/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}});
    updateSlotState(false);
    setStatus('Monitoring stopped.','var(--text-muted)');
}

startPolling();
</script>
@endsection
