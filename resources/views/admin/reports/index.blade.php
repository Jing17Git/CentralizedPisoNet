@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="value">₱{{ number_format($totalEarningsToday, 2) }}</div><div class="label">Earnings Today</div></div>
    <div class="stat-card"><div class="value">{{ $totalCoinsToday }}</div><div class="label">Coins Today</div></div>
    <div class="stat-card"><div class="value">{{ $activeMachines }}</div><div class="label">Active Machines</div></div>
    <div class="stat-card"><div class="value">{{ $totalSessionsToday }}</div><div class="label">Sessions Today</div></div>
</div>
<div class="card">
    <h3>Daily Earnings (Last 7 Days)</h3>
    <table>
        <thead><tr><th>Date</th><th>Total Earnings</th></tr></thead>
        <tbody>
        @forelse($dailyEarnings as $day)
            <tr><td>{{ $day->date }}</td><td>₱{{ number_format($day->total, 2) }}</td></tr>
        @empty
            <tr><td colspan="2" style="text-align:center;color:#666;">No data yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="card">
    <h3>Machine Usage</h3>
    <table>
        <thead><tr><th>Machine</th><th>Code</th><th>Status</th><th>Sessions</th></tr></thead>
        <tbody>
        @forelse($machineUsage as $machine)
            <tr>
                <td>{{ $machine->name }}</td>
                <td>{{ $machine->machine_code }}</td>
                <td><span class="badge {{ $machine->status === 'online' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($machine->status) }}</span></td>
                <td>{{ $machine->sessions_count }}</td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;color:#666;">No machines yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
