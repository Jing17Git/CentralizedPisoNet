<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PisoNet Admin — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* =============================================
           COLOR PALETTE (4-color professional system)
           --bg-deep    #0D1117   (deepest background)
           --bg-card    #161B22   (card / sidebar bg)
           --accent     #58A6FF   (primary accent / CTA)
           --text       #E6EDF3   (primary text)
           -- plus semantic: success #3FB950, danger #F85149, warning #D29922
        ============================================= */

        :root {
            --bg-deep:    #0D1117;
            --bg-card:    #161B22;
            --bg-hover:   #1C2128;
            --border:     #30363D;
            --accent:     #58A6FF;
            --accent-dim: rgba(88,166,255,.12);
            --text:       #E6EDF3;
            --text-muted: #8B949E;
            --success:    #3FB950;
            --danger:     #F85149;
            --warning:    #D29922;
            --sidebar-w:  240px;
            --topbar-h:   56px;
            --radius:     8px;
            --radius-sm:  5px;
            --shadow:     0 4px 24px rgba(0,0,0,.35);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { font-size: 14px; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-deep);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            line-height: 1.5;
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand-icon {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: .4px;
        }

        .sidebar-brand-sub {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 12px 0;
            flex: 1;
        }

        .nav-section-label {
            padding: 10px 18px 4px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: 0;
            transition: background .15s, color .15s;
            position: relative;
        }

        .sidebar a .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar a:hover {
            background: var(--bg-hover);
            color: var(--text);
        }

        .sidebar a.active {
            background: var(--accent-dim);
            color: var(--accent);
        }

        .sidebar a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        /* ─── MAIN ─── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 100vh;
        }

        /* ─── TOPBAR ─── */
        .topbar {
            height: var(--topbar-h);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            background: var(--bg-card);
            position: sticky;
            top: 0;
            z-index: 10;
            gap: 16px;
        }

        .topbar h1 {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .topbar-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%,100% { opacity: 1; }
            50% { opacity: .4; }
        }

        .topbar form button {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 12px;
            font-family: inherit;
            font-weight: 500;
            transition: background .15s, color .15s, border-color .15s;
        }

        .topbar form button:hover {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        /* ─── CONTENT AREA ─── */
        .content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        /* ─── ALERTS ─── */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(63,185,80,.1);
            color: var(--success);
            border-color: rgba(63,185,80,.25);
        }

        .alert-error {
            background: rgba(248,81,73,.1);
            color: var(--danger);
            border-color: rgba(248,81,73,.25);
        }

        /* ─── CARD ─── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .card h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card h3 .update-ts {
            font-size: 11px;
            font-weight: 400;
            color: var(--text-muted);
        }

        /* ─── TABLE ─── */
        table { width: 100%; border-collapse: collapse; }

        thead tr {
            border-bottom: 1px solid var(--border);
        }

        th {
            padding: 8px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        td {
            padding: 10px 12px;
            font-size: 13px;
            color: var(--text);
            border-bottom: 1px solid rgba(48,54,61,.6);
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr:hover td { background: var(--bg-hover); }

        .td-empty {
            text-align: center;
            color: var(--text-muted) !important;
            padding: 24px !important;
        }

        /* ─── STAT GRID ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            transition: border-color .2s;
        }

        .stat-card:hover { border-color: var(--accent); }

        .stat-card .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--accent);
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
        }

        .badge::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .badge-success {
            background: rgba(63,185,80,.12);
            color: var(--success);
        }
        .badge-success::before { background: var(--success); }

        .badge-danger {
            background: rgba(248,81,73,.12);
            color: var(--danger);
        }
        .badge-danger::before { background: var(--danger); }

        .badge-warning {
            background: rgba(210,153,34,.12);
            color: var(--warning);
        }
        .badge-warning::before { background: var(--warning); }

        .badge-info {
            background: rgba(88,166,255,.12);
            color: var(--accent);
        }
        .badge-info::before { background: var(--accent); }

        /* ─── BUTTONS ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: opacity .15s, background .15s, border-color .15s, color .15s;
            white-space: nowrap;
        }

        .btn:hover { opacity: .85; }

        .btn-primary {
            background: var(--accent);
            color: #0D1117;
            border-color: var(--accent);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        .btn-success {
            background: var(--success);
            color: #0D1117;
            border-color: var(--success);
        }

        .btn-warning {
            background: var(--warning);
            color: #0D1117;
            border-color: var(--warning);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border-color: var(--border);
        }

        .btn-outline:hover {
            color: var(--text);
            border-color: var(--text-muted);
            opacity: 1;
        }

        .btn-outline.active-filter {
            background: var(--accent-dim);
            color: var(--accent);
            border-color: var(--accent);
        }

        /* ─── FORM CONTROLS ─── */
        label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        input, select, textarea {
            background: var(--bg-deep);
            color: var(--text);
            border: 1px solid var(--border);
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            width: 100%;
            margin-bottom: 14px;
            font-size: 13px;
            font-family: inherit;
            transition: border-color .15s;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(88,166,255,.1);
        }

        select option { background: var(--bg-card); }

        .form-group { margin-bottom: 14px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .field-error {
            color: var(--danger);
            font-size: 11px;
            margin-top: -10px;
            margin-bottom: 10px;
            display: block;
        }

        /* ─── SECTION HEADER ─── */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 18px;
            font-weight: 700;
        }

        /* ─── FILTER BAR ─── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .filter-bar span {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
    </style>
</head>
<body>

<!-- ─── SIDEBAR ─── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">⚡</div>
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">PisoNet</span>
            <span class="sidebar-brand-sub">Admin Panel</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">▣</span> Dashboard
        </a>
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="nav-icon">◈</span> Reports
        </a>

        <div class="nav-section-label">Management</div>
        <a href="{{ route('pc-units.index') }}" class="{{ request()->routeIs('pc-units.*') ? 'active' : '' }}">
            <span class="nav-icon">▨</span> PC Units
        </a>
        <a href="{{ route('sessions.index') }}" class="{{ request()->routeIs('sessions.*') ? 'active' : '' }}">
            <span class="nav-icon">◷</span> Sessions
        </a>
        <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <span class="nav-icon">◈</span> Transactions
        </a>
        <a href="{{ route('coin-inserts.index') }}" class="{{ request()->routeIs('coin-inserts.*') ? 'active' : '' }}">
            <span class="nav-icon">⬡</span> Coin Inserts
        </a>

        <div class="nav-section-label">Configuration</div>
        <a href="{{ route('rates.index') }}" class="{{ request()->routeIs('rates.*') ? 'active' : '' }}">
            <span class="nav-icon">◲</span> Rates
        </a>
        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <span class="nav-icon">⚙</span> Settings
        </a>
        <a href="{{ route('lock.index') }}" class="{{ request()->routeIs('lock.*') ? 'active' : '' }}">
            <span class="nav-icon">◉</span> Lock Screen
        </a>

        <div class="nav-section-label">Diagnostics</div>
        <a href="{{ route('coin-test.index') }}" class="{{ request()->routeIs('coin-test.*') ? 'active' : '' }}">
            <span class="nav-icon">▷</span> Coin Test
        </a>

        <div class="nav-section-label">Account</div>
        <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="nav-icon">◎</span> Profile
        </a>
    </nav>
</aside>

<!-- ─── MAIN ─── -->
<div class="main">
    <!-- TOPBAR -->
    <header class="topbar">
        <h1>@yield('title', 'Dashboard')</h1>
        <div class="topbar-right">
            <div class="topbar-badge">
                <span class="dot"></span>
                <span>Live</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" id="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <!-- ALERTS -->
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✕ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>
