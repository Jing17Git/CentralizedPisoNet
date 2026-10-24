@extends('layouts.app')
@section('title', 'Reports')
@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">₱{{ number_format($totalEarningsToday, 2) }}</div>
        <div class="stat-label">Earnings Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalCoinsToday }}</div>
        <div class="stat-label">Coins Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $activeMachines }}</div>
        <div class="stat-label">Active Machines</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalSessionsToday }}</div>
        <div class="stat-label">Sessions Today</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>◈ Daily Earnings — Last 7 Days</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Earnings</th>
            </tr>
        </thead>
        <tbody>
        @forelse($dailyEarnings as $day)
            <tr>
                <td>{{ $day->date }}</td>
                <td>₱{{ number_format($day->total, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="2" class="td-empty">No data yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h3>▨ Machine Usage</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Machine</th>
                <th>Code</th>
                <th>Status</th>
                <th>Sessions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($machineUsage as $machine)
            <tr>
                <td>{{ $machine->name }}</td>
                <td>{{ $machine->machine_code }}</td>
                <td><span class="badge {{ $machine->status === 'online' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($machine->status) }}</span></td>
                <td>{{ $machine->sessions_count }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="td-empty">No machines yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
