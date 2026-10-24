@extends('layouts.app')
@section('title', 'Coin Inserts')
@section('content')
<div style="margin-bottom:16px;">
    <a href="?filter=daily" class="btn {{ $filter==='daily' ? 'btn-primary' : '' }}" style="margin-right:8px;">Daily</a>
    <a href="?filter=weekly" class="btn {{ $filter==='weekly' ? 'btn-primary' : '' }}" style="margin-right:8px;">Weekly</a>
    <a href="?filter=monthly" class="btn {{ $filter==='monthly' ? 'btn-primary' : '' }}">Monthly</a>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="value">{{ $totalInserts }}</div><div class="label">Total Inserts</div></div>
    <div class="stat-card"><div class="value">₱{{ number_format($totalCoinValue, 2) }}</div><div class="label">Total Value</div></div>
    <div class="stat-card"><div class="value">{{ $totalMinutes }}</div><div class="label">Total Minutes</div></div>
</div>
<div class="card">
    <h3>Coin Inserts</h3>
    <table>
        <thead><tr><th>Coin ID</th><th>PC Unit</th><th>Value</th><th>Minutes</th><th>Time</th></tr></thead>
        <tbody>
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
@endsection
