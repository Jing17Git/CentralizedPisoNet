@extends('layouts.app')
@section('title', 'Coin Inserts')
@section('content')
<div style="margin-bottom:16px;">
    <a href="?filter=daily" class="btn {{ $filter==='daily' ? 'btn-primary' : '' }}" style="margin-right:8px;">Daily</a>
    <a href="?filter=weekly" class="btn {{ $filter==='weekly' ? 'btn-primary' : '' }}" style="margin-right:8px;">Weekly</a>
    <a href="?filter=monthly" class="btn {{ $filter==='monthly' ? 'btn-primary' : '' }}">Monthly</a>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="value" id="totalInserts">{{ $totalInserts }}</div><div class="label">Total Inserts</div></div>
    <div class="stat-card"><div class="value" id="totalCoinValue">₱{{ number_format($totalCoinValue, 2) }}</div><div class="label">Total Value</div></div>
    <div class="stat-card"><div class="value" id="totalMinutes">{{ $totalMinutes }}</div><div class="label">Total Minutes</div></div>
</div>
<div class="card">
    <h3>Coin Inserts <span style="font-size:12px;color:#666;" id="lastUpdate"></span></h3>
    <table>
        <thead><tr><th>Coin ID</th><th>PC Unit</th><th>Value</th><th>Minutes</th><th>Time</th></tr></thead>
        <tbody id="coinInsertsTable">
        @forelse($coinInserts as $coin)
            <tr>
                <td>{{ $coin->coin_id }}</td>
                <td>{{ $coin->pc_unit_id }}</td>
                <td>₱{{ number_format($coin->coin_value, 2) }}</td>
                <td>{{ $coin->minutes_given }}</td>
                <td>{{ $coin->inserted_time }}</td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;color:#666;">No coin inserts yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateCoinInserts() {
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || '';
    const url = '{{ route("coin-inserts.index") }}' + (filter ? '?filter=' + filter : '');
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const inserts = doc.querySelector('#totalInserts');
            if (inserts) document.getElementById('totalInserts').innerHTML = inserts.innerHTML;
            
            const value = doc.querySelector('#totalCoinValue');
            if (value) document.getElementById('totalCoinValue').innerHTML = value.innerHTML;
            
            const minutes = doc.querySelector('#totalMinutes');
            if (minutes) document.getElementById('totalMinutes').innerHTML = minutes.innerHTML;
            
            const table = doc.querySelector('#coinInsertsTable');
            if (table) document.getElementById('coinInsertsTable').innerHTML = table.innerHTML;
            
            const now = new Date();
            document.getElementById('lastUpdate').textContent = '(Updated: ' + now.toLocaleTimeString() + ')';
        })
        .catch(err => console.error('Update failed:', err));
}

updateInterval = setInterval(updateCoinInserts, 3000);

window.addEventListener('beforeunload', () => {
    if (updateInterval) clearInterval(updateInterval);
});
</script>
@endsection
