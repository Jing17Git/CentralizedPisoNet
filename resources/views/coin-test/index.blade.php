@extends('layouts.app')
@section('title', 'Coin Test')
@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Left: Monitor --}}
    <div class="card" style="text-align:center;">
        <h3>🧪 Coin Slot Test</h3>
        <p style="color:#aaa;font-size:13px;margin-bottom:16px;">Insert coins then click <b style="color:#2ecc71;">Done</b> to confirm.</p>

        <div style="margin-bottom:16px;">
            <label style="text-align:left;">Select Machine</label>
            <select id="machineSelect" onchange="resetMonitor()">
                @foreach($machines as $machine)
                    <option value="{{ $machine->machine_code }}" {{ request('machine')==$machine->machine_code?'selected':'' }}>
                        {{ $machine->machine_name }} ({{ $machine->machine_code }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Session Timer --}}
        <div style="background:#0f3460;border-radius:8px;padding:20px;margin-bottom:12px;">
            <div style="font-size:12px;color:#aaa;margin-bottom:4px;">Session Remaining (HH:MM)</div>
            <div id="timeDisplay" style="font-size:56px;font-weight:bold;color:#00c8ff;letter-spacing:3px;transition:color 0.4s;">--:--</div>
            <div id="sessionId" style="font-size:11px;color:#555;margin-top:6px;">No active session</div>
            <div id="startTime" style="font-size:11px;color:#555;"></div>
        </div>

        {{-- Pending Coins Box --}}
        <div id="pendingBox" style="background:#1a3a1a;border:2px solid #2ecc71;border-radius:8px;padding:16px;margin-bottom:12px;display:none;">
            <div style="font-size:12px;color:#aaa;margin-bottom:6px;">⏳ Coins Waiting — Click Done to confirm</div>
            <div id="pendingCoins"   style="font-size:28px;font-weight:bold;color:#2ecc71;"></div>
            <div id="pendingMinutes" style="font-size:14px;color:#2ecc71;font-weight:bold;margin-top:4px;"></div>
            <div id="pendingAmount"  style="font-size:12px;color:#aaa;margin-top:2px;"></div>
            <div style="display:flex;gap:10px;margin-top:12px;justify-content:center;">
                <button class="btn btn-success" onclick="donePending()" style="flex:1;padding:12px;font-size:15px;font-weight:bold;">✅ Done</button>
                <button class="btn btn-danger"  onclick="cancelPending()" style="flex:1;padding:12px;font-size:15px;">✖ Cancel</button>
            </div>
        </div>

        {{-- Coin Slot State --}}
        <div id="slotStateBox" style="display:flex;align-items:center;justify-content:center;gap:10px;padding:10px;border-radius:6px;background:#1a1a2e;margin-bottom:8px;">
            <div id="slotDot" style="width:14px;height:14px;border-radius:50%;background:#555;flex-shrink:0;transition:background 0.3s;"></div>
            <span id="slotLabel" style="font-size:13px;color:#555;font-weight:bold;">Coin Slot: UNKNOWN</span>
        </div>

        {{-- Status --}}
        <div id="statusBox" style="padding:12px;border-radius:6px;background:#1a1a2e;color:#aaa;font-size:13px;min-height:40px;">
            Waiting for coin...
        </div>

        <div style="margin-top:16px;display:flex;gap:10px;justify-content:center;">
            <button class="btn btn-success" onclick="startPolling()">▶ Start</button>
            <button class="btn btn-danger"  onclick="stopPolling()">■ Stop</button>
            <button class="btn btn-warning" id="resetBtn" onclick="resetSession()" style="display:none;">🔄 Reset Session</button>
        </div>
    </div>

    {{-- Right: Rates + History --}}
    <div>
        <div class="card" style="margin-bottom:20px;">
            <h3>💲 Current Rates</h3>
            <table><thead><tr><th>Coin</th><th>Minutes Given</th></tr></thead>
            <tbody id="ratesBody"><tr><td colspan="2" style="color:#555;text-align:center;">Loading...</td></tr></tbody></table>
            <a href="{{ route('rates.index') }}" class="btn btn-warning" style="margin-top:12px;display:block;text-align:center;">⚙️ Edit Rates</a>
        </div>

        <div class="card">
            <h3>📋 Today's Coin History</h3>
            <div style="max-height:250px;overflow-y:auto;">
                <table><thead><tr><th>Time</th><th>Coin</th><th>Minutes</th><th>Amount</th></tr></thead>
                <tbody id="coinLogBody"><tr><td colspan="4" style="color:#555;text-align:center;">No coins today</td></tr></tbody></table>
            </div>
            <div id="todaySummary" style="margin-top:10px;font-size:12px;color:#aaa;"></div>
        </div>
    </div>
</div>

<script>
var pollInterval=null, lastRemaining=null, lastSessionId=null, lastCoinCount=0, activeSession=null;
var csrf='{{ csrf_token() }}';

function machine(){ return document.getElementById('machineSelect').value; }
function setStatus(m,c){ var b=document.getElementById('statusBox'); b.style.color=c||'#aaa'; b.textContent=m; }
function fmt(min){ return String(Math.floor(min/60)).padStart(2,'0')+':'+String(min%60).padStart(2,'0'); }
function flash(c){ var d=document.getElementById('timeDisplay'); d.style.color=c; setTimeout(function(){ d.style.color='#00c8ff'; },1500); }

function updateRates(r){
    if(!r) return;
    document.getElementById('ratesBody').innerHTML=Object.entries(r).map(function(e){
        return '<tr><td>₱'+e[0]+'</td><td style="color:#2ecc71;font-weight:bold;">'+e[1]+' minutes</td></tr>';
    }).join('');
}

function updateHistory(coins){
    var tbody=document.getElementById('coinLogBody');
    if(!coins||coins.length===0){
        tbody.innerHTML='<tr><td colspan="4" style="color:#555;text-align:center;">No coins today</td></tr>';
        document.getElementById('todaySummary').textContent='';
        lastCoinCount=0; return;
    }
    if(lastCoinCount>0 && coins.length>lastCoinCount){
        var l=coins[0];
        flash('#2ecc71');
        setStatus('✅ Done! +'+l.minutes_purchased+' minutes added','#2ecc71');
    }
    lastCoinCount=coins.length;
    tbody.innerHTML=coins.map(function(c){
        var t=new Date(c.created_at).toLocaleTimeString();
        return '<tr><td>'+t+'</td><td>₱'+parseFloat(c.amount).toFixed(0)+'</td><td style="color:#2ecc71;font-weight:bold;">+'+c.minutes_purchased+' min</td><td>₱'+parseFloat(c.amount).toFixed(2)+'</td></tr>';
    }).join('');
    var tm=coins.reduce(function(s,c){return s+c.minutes_purchased;},0);
    var ta=coins.reduce(function(s,c){return s+parseFloat(c.amount);},0);
    document.getElementById('todaySummary').textContent='Total: '+coins.length+' transaction(s) | '+tm+' min | ₱'+ta.toFixed(2);
}

function updatePending(pending){
    var box=document.getElementById('pendingBox');
    if(pending && pending.coins>0){
        box.style.display='block';
        document.getElementById('pendingCoins').textContent=pending.coins+' coin(s) inserted';
        document.getElementById('pendingMinutes').textContent='+'+pending.minutes+' minutes will be added';
        document.getElementById('pendingAmount').textContent='Total: ₱'+parseFloat(pending.amount).toFixed(2);
        setStatus('🪙 Coins waiting — click Done to confirm!','#f39c12');
    } else {
        box.style.display='none';
    }
}

function updateSlotState(open){
    var dot=document.getElementById('slotDot');
    var lbl=document.getElementById('slotLabel');
    if(open){
        dot.style.background='#2ecc71';
        lbl.style.color='#2ecc71';
        lbl.textContent='Coin Slot: OPEN — accepting coins';
    } else {
        dot.style.background='#e74c3c';
        lbl.style.color='#e74c3c';
        lbl.textContent='Coin Slot: CLOSED — returning coins';
    }
}

function poll(){
    fetch('/coin-test/session/'+machine()).then(function(r){return r.json();}).then(function(data){
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
            document.getElementById('resetBtn').style.display='inline-block';
            if(data.pending && data.pending.coins>0){
                // pending coins take priority over session status text
            } else if(lastSessionId!==data.session_id){lastSessionId=data.session_id;lastRemaining=rem;setStatus('✅ Active — '+fmt(rem)+' remaining','#2ecc71');}
            else if(lastRemaining!==null && rem>lastRemaining){var a=rem-lastRemaining;setStatus('✅ +'+a+' minutes added! Now: '+fmt(rem),'#2ecc71');flash('#2ecc71');}
            lastRemaining=rem;
            lastSessionId=data.session_id;
        } else {
            activeSession=null;
            document.getElementById('timeDisplay').textContent='--:--';
            document.getElementById('sessionId').textContent='No active session';
            document.getElementById('startTime').textContent='';
            document.getElementById('resetBtn').style.display='none';
            if(lastSessionId!==null){setStatus('Session ended. Insert coins to start.','#f39c12');lastSessionId=null;lastRemaining=null;}
            else if(!data.pending||data.pending.coins===0){ setStatus('Waiting for coin...','#aaa'); }
        }
    }).catch(function(e){ setStatus('Error: '+e.message,'#e74c3c'); });
}

function donePending(){
    fetch('/coin-test/done/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){
            document.getElementById('pendingBox').style.display='none';
            setStatus('\u2705 Done! +'+d.minutes+' minutes added','#2ecc71');
            flash('#2ecc71');
            document.getElementById('timeDisplay').textContent=fmt(d.remaining_time);
            lastRemaining=d.remaining_time;
            lastSessionId=null;
        } else { setStatus('Error: '+(d.message||'failed'),'#e74c3c'); }
    });
}

function cancelPending(){
    fetch('/coin-test/cancel/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}})
    .then(function(r){return r.json();}).then(function(d){
        document.getElementById('pendingBox').style.display='none';
        setStatus('Cancelled. Waiting for coin...','#aaa');
    });
}

function resetSession(){
    if(!activeSession) return;
    if(!confirm('Reset session? This will end the current session.')) return;
    fetch('/coin-test/reset/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){lastSessionId=null;lastRemaining=null;activeSession=null;document.getElementById('timeDisplay').textContent='--:--';document.getElementById('sessionId').textContent='No active session';document.getElementById('resetBtn').style.display='none';setStatus('Session reset.','#2ecc71');}
        else{ setStatus('Reset failed: '+(d.message||'error'),'#e74c3c'); }
    });
}

function resetMonitor(){lastRemaining=null;lastSessionId=null;lastCoinCount=0;activeSession=null;document.getElementById('timeDisplay').textContent='--:--';document.getElementById('sessionId').textContent='No active session';document.getElementById('startTime').textContent='';document.getElementById('pendingBox').style.display='none';document.getElementById('resetBtn').style.display='none';setStatus('Waiting for coin...','#aaa');if(pollInterval){stopPolling();startPolling();}}
function startPolling(){ fetch('/coin-test/open-slot/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}}); updateSlotState(true); if(pollInterval)return; poll(); pollInterval=setInterval(poll,2000); setStatus('Monitoring — insert a coin!','#2ecc71'); }
function stopPolling(){ clearInterval(pollInterval); pollInterval=null; fetch('/coin-test/close-slot/'+machine(),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json'}}); updateSlotState(false); setStatus('Monitoring stopped.','#aaa'); }
startPolling();
</script>
@endsection
