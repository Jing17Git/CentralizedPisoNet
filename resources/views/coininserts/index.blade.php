@extends('layouts.app')
@section('title', 'Coin Inserts')
@section('content')

<div class="filter-bar">
    <span>Filter:</span>
    <a href="?filter=daily"   class="btn btn-outline {{ $filter==='daily'   ? 'active-filter' : '' }}" id="filter-daily">Daily</a>
    <a href="?filter=weekly"  class="btn btn-outline {{ $filter==='weekly'  ? 'active-filter' : '' }}" id="filter-weekly">Weekly</a>
    <a href="?filter=monthly" class="btn btn-outline {{ $filter==='monthly' ? 'active-filter' : '' }}" id="filter-monthly">Monthly</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value" id="totalInserts">{{ $totalInserts }}</div>
        <div class="stat-label">Total Inserts</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalCoinValue">₱{{ number_format($totalCoinValue, 2) }}</div>
        <div class="stat-label">Total Value</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalMinutes">{{ $totalMinutes }}</div>
        <div class="stat-label">Total Minutes</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>⬡ Coin Inserts <span class="update-ts" id="lastUpdate"></span></h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Coin ID</th>
                <th>PC Unit</th>
                <th>Value</th>
                <th>Minutes</th>
                <th>Inserted At</th>
            </tr>
        </thead>
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
            <tr><td colspan="5" class="td-empty">No coin inserts yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
let updateInterval;

function updateCoinInserts() {
    const filter = new URLSearchParams(window.location.search).get('filter') || '';
    const url = '{{ route("coin-inserts.index") }}' + (filter ? '?filter=' + filter : '');

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            ['totalInserts','totalCoinValue','totalMinutes','coinInsertsTable'].forEach(id => {
                const src = doc.getElementById(id);
                const dst = document.getElementById(id);
                if (src && dst) dst.innerHTML = src.innerHTML;
            });
            const el = document.getElementById('lastUpdate');
            if (el) el.textContent = 'Updated ' + new Date().toLocaleTimeString();
        })
        .catch(err => console.error('Coin inserts update failed:', err));
}

updateInterval = setInterval(updateCoinInserts, 5000);
window.addEventListener('beforeunload', () => { if (updateInterval) clearInterval(updateInterval); });
</script>
@endsection
