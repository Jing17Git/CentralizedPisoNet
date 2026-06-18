<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CentralizedPisoNet - @yield('title', 'Dashboard')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a2e; color: #eee; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #16213e; padding: 20px 0; flex-shrink: 0; }
        .sidebar h2 { color: #00c8ff; text-align: center; padding: 10px 20px 20px; font-size: 16px; border-bottom: 1px solid #0f3460; }
        .sidebar a { display: block; padding: 12px 20px; color: #aaa; text-decoration: none; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { background: #0f3460; color: #00c8ff; }
        .main { flex: 1; padding: 20px; overflow-y: auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .topbar h1 { font-size: 20px; color: #00c8ff; }
        .topbar form button { background: #e74c3c; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 16px; }
        .alert-success { background: #1e4d2b; color: #2ecc71; border: 1px solid #2ecc71; }
        .alert-error { background: #4d1e1e; color: #e74c3c; border: 1px solid #e74c3c; }
        .card { background: #16213e; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .card h3 { color: #00c8ff; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #0f3460; font-size: 13px; }
        th { color: #00c8ff; font-weight: 600; }
        td { color: #ccc; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 13px; cursor: pointer; border: none; }
        .btn-primary { background: #00c8ff; color: #000; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-success { background: #2ecc71; color: #000; }
        .btn-warning { background: #f39c12; color: #000; }
        input, select, textarea { background: #0f3460; color: #eee; border: 1px solid #1a4a7a; padding: 8px 12px; border-radius: 4px; width: 100%; margin-bottom: 12px; font-size: 14px; }
        label { display: block; color: #aaa; margin-bottom: 4px; font-size: 13px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .stat-card { background: #16213e; border-radius: 8px; padding: 16px; text-align: center; }
        .stat-card .value { font-size: 28px; font-weight: bold; color: #00c8ff; }
        .stat-card .label { font-size: 12px; color: #aaa; margin-top: 4px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; }
        .badge-success { background: #1e4d2b; color: #2ecc71; }
        .badge-danger { background: #4d1e1e; color: #e74c3c; }
        .badge-warning { background: #4d3a1e; color: #f39c12; }
        .badge-info { background: #1e3a4d; color: #00c8ff; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>⚡ PisoNet Admin</h2>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
    <a href="{{ route('pc-units.index') }}" class="{{ request()->routeIs('pc-units.*') ? 'active' : '' }}">🖥️ PC Units</a>
    <a href="{{ route('sessions.index') }}" class="{{ request()->routeIs('sessions.*') ? 'active' : '' }}">⏱️ Sessions</a>
    <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">💰 Transactions</a>
    <a href="{{ route('coin-inserts.index') }}" class="{{ request()->routeIs('coin-inserts.*') ? 'active' : '' }}">🪙 Coin Inserts</a>
    <a href="{{ route('coin-test.index') }}" class="{{ request()->routeIs('coin-test.*') ? 'active' : '' }}">🧪 Coin Test</a>
    <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">📊 Reports</a>
    <a href="{{ route('rates.index') }}" class="{{ request()->routeIs('rates.*') ? 'active' : '' }}">💲 Rates</a>
    <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">⚙️ Settings</a>
    <a href="{{ route('lock.index') }}" class="{{ request()->routeIs('lock.*') ? 'active' : '' }}">🔒 Lock Screen</a>
    <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">👤 Profile</a>
</div>
<div class="main">
    <div class="topbar">
        <h1>@yield('title', 'Dashboard')</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</div>
</body>
</html>
