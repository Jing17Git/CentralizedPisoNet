<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'PisoNet Central — Admin Dashboard')</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg-deep:     #040c18;
    --bg-mid:      #071425;
    --bg-card:     rgba(6, 22, 44, 0.72);
    --neon:        #00c8ff;
    --neon2:       #0077ff;
    --neon-glow:   0 0 18px #00c8ff88, 0 0 40px #0077ff44;
    --accent-teal: #00ffcc;
    --accent-warn: #ffb400;
    --accent-red:  #ff3b6b;
    --accent-grn:  #00e676;
    --text-hi:     #e8f4ff;
    --text-mid:    #7fa8c9;
    --text-lo:     #3a5570;
    --border:      rgba(0,200,255,0.12);
    --glass:       rgba(0,180,255,0.06);
    --radius:      14px;
    --sidebar-w:   230px;
    --topbar-h:    62px;
  }

  html, body { height: 100%; }
  body {
    font-family: 'Syne', sans-serif;
    background: var(--bg-deep);
    color: var(--text-hi);
    overflow-x: hidden;
    min-height: 100vh;
  }

  /* ── Background mesh ── */
  body::before {
    content: '';
    position: fixed; inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 15% 10%,  rgba(0,120,255,0.14) 0%, transparent 60%),
      radial-gradient(ellipse 60% 50% at 85% 80%,  rgba(0,200,255,0.10) 0%, transparent 60%),
      radial-gradient(ellipse 40% 40% at 50% 50%,  rgba(0,50,120,0.18) 0%, transparent 70%);
    pointer-events: none; z-index: 0;
  }

  /* ── Grid scan-line overlay ── */
  body::after {
    content: '';
    position: fixed; inset: 0;
    background-image:
      linear-gradient(rgba(0,200,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,200,255,0.025) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none; z-index: 0;
  }

  /* ══════════════ SIDEBAR ══════════════ */
  .sidebar {
    position: fixed; top: 0; left: 0;
    width: var(--sidebar-w); height: 100vh;
    background: rgba(4,14,28,0.92);
    border-right: 1px solid var(--border);
    backdrop-filter: blur(18px);
    display: flex; flex-direction: column;
    z-index: 100;
    overflow: hidden;
  }
  .sidebar::before {
    content: '';
    position: absolute; top: -60px; left: -30px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(0,200,255,0.15) 0%, transparent 70%);
    pointer-events: none;
  }

  .brand {
    display: flex; align-items: center; gap: 10px;
    padding: 20px 22px 14px;
    border-bottom: 1px solid var(--border);
  }
  .brand-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    box-shadow: var(--neon-glow);
    flex-shrink: 0;
  }
  .brand-text { line-height: 1; }
  .brand-name { font-size: 15px; font-weight: 800; color: var(--text-hi); letter-spacing: 0.5px; }
  .brand-sub  { font-size: 10px; color: var(--neon); font-family: 'DM Mono', monospace; margin-top: 2px; letter-spacing: 1px; }

  .nav-section { padding: 18px 14px 4px; }
  .nav-label {
    font-size: 9px; letter-spacing: 2px; color: var(--text-lo);
    font-family: 'DM Mono', monospace; text-transform: uppercase;
    padding: 0 8px; margin-bottom: 6px;
  }
  .nav-item {
    display: flex; align-items: center; gap: 11px;
    padding: 9px 12px; border-radius: 10px;
    cursor: pointer; transition: all .2s ease;
    color: var(--text-mid); font-size: 13px; font-weight: 500;
    position: relative; margin-bottom: 2px;
  }
  .nav-item:hover  { background: var(--glass); color: var(--text-hi); }
  .nav-item.active {
    background: linear-gradient(90deg, rgba(0,120,255,0.22), rgba(0,200,255,0.10));
    color: var(--neon);
    border: 1px solid rgba(0,200,255,0.2);
  }
  .nav-item.active::before {
    content: '';
    position: absolute; left: -1px; top: 20%; bottom: 20%;
    width: 3px; border-radius: 0 3px 3px 0;
    background: var(--neon);
    box-shadow: 0 0 8px var(--neon);
  }
  .nav-icon { font-size: 16px; width: 20px; text-align: center; }
  .nav-badge {
    margin-left: auto; background: var(--neon2);
    color: #fff; font-size: 10px; padding: 1px 6px;
    border-radius: 20px; font-family: 'DM Mono', monospace;
  }

  .sidebar-footer {
    margin-top: auto;
    padding: 16px;
    border-top: 1px solid var(--border);
  }
  .user-chip {
    display: flex; align-items: center; gap: 10px;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 10px; padding: 9px 12px;
    cursor: pointer;
  }
  .user-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, var(--neon2), var(--accent-teal));
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: var(--bg-deep);
    flex-shrink: 0;
  }
  .user-info { flex: 1; overflow: hidden; }
  .user-name { font-size: 12px; font-weight: 600; color: var(--text-hi); }
  .user-role { font-size: 10px; color: var(--neon); font-family: 'DM Mono', monospace; }

  /* ══════════════ TOPBAR ══════════════ */
  .topbar {
    position: fixed; top: 0; left: var(--sidebar-w);
    right: 0; height: var(--topbar-h);
    background: rgba(4,14,28,0.88);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; padding: 0 28px;
    z-index: 90; gap: 16px;
  }
  .topbar-title { font-size: 18px; font-weight: 700; flex: 1; }
  .topbar-title span { color: var(--neon); }
  .search-bar {
    display: flex; align-items: center; gap: 8px;
    background: var(--glass); border: 1px solid var(--border);
    border-radius: 9px; padding: 7px 14px;
    width: 220px;
  }
  .search-bar input {
    background: none; border: none; outline: none;
    color: var(--text-hi); font-family: 'Syne', sans-serif;
    font-size: 12px; width: 100%;
  }
  .search-bar input::placeholder { color: var(--text-lo); }
  .search-icon { color: var(--text-lo); font-size: 14px; }

  .topbar-actions { display: flex; align-items: center; gap: 10px; }
  .icon-btn {
    width: 36px; height: 36px; border-radius: 9px;
    background: var(--glass); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 16px; color: var(--text-mid);
    transition: all .2s; position: relative;
  }
  .icon-btn:hover { border-color: var(--neon); color: var(--neon); box-shadow: 0 0 12px rgba(0,200,255,0.2); }
  .notif-dot {
    position: absolute; top: 5px; right: 5px;
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--accent-red);
    box-shadow: 0 0 6px var(--accent-red);
    border: 1.5px solid var(--bg-deep);
  }
  .top-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--neon2), var(--accent-teal));
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; color: var(--bg-deep);
    cursor: pointer; border: 2px solid rgba(0,200,255,0.4);
    box-shadow: 0 0 12px rgba(0,200,255,0.3);
  }
  .live-dot {
    display: flex; align-items: center; gap: 6px;
    font-family: 'DM Mono', monospace; font-size: 11px; color: var(--accent-grn);
    background: rgba(0,230,118,0.08); border: 1px solid rgba(0,230,118,0.2);
    border-radius: 20px; padding: 4px 12px;
  }
  .live-dot::before {
    content: '';
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--accent-grn);
    box-shadow: 0 0 6px var(--accent-grn);
    animation: pulse 1.5s ease-in-out infinite;
  }
  @keyframes pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.5; transform:scale(1.4); }
  }

  /* ══════════════ MAIN ══════════════ */
  .main {
    margin-left: var(--sidebar-w);
    padding-top: var(--topbar-h);
    min-height: 100vh;
    position: relative; z-index: 1;
    padding-bottom: 40px;
  }
  .page { padding: 28px 28px 0; }

  /* ── Stats row ── */
  .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 22px;
  }
  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px 22px;
    backdrop-filter: blur(16px);
    position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    cursor: default;
  }
  .stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 24px rgba(0,200,255,0.08);
  }
  .stat-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
  }
  .stat-card.c1::before { background: linear-gradient(90deg, var(--neon2), var(--neon)); }
  .stat-card.c2::before { background: linear-gradient(90deg, #9b59b6, #e91e8c); }
  .stat-card.c3::before { background: linear-gradient(90deg, var(--accent-warn), #ff6b00); }
  .stat-card.c4::before { background: linear-gradient(90deg, var(--accent-grn), #00bcd4); }

  .stat-label { font-size: 11px; color: var(--text-mid); letter-spacing: 0.5px; margin-bottom: 8px; font-weight: 500; }
  .stat-value { font-size: 28px; font-weight: 800; color: var(--text-hi); line-height: 1; margin-bottom: 8px; }
  .stat-value .unit { font-size: 14px; font-weight: 500; color: var(--text-mid); margin-left: 2px; }
  .stat-meta {
    display: flex; align-items: center; gap: 6px;
    font-size: 11px; font-family: 'DM Mono', monospace;
  }
  .up   { color: var(--accent-grn); }
  .down { color: var(--accent-red); }
  .stat-icon {
    position: absolute; right: 18px; top: 18px;
    font-size: 32px; opacity: 0.12;
  }
  .stat-spark { position: absolute; bottom: 0; right: 0; opacity: 0.25; }

  /* ── Middle row ── */
  .mid-row {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 16px; margin-bottom: 22px;
  }

  /* Machine Grid */
  .glass-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    backdrop-filter: blur(16px);
    padding: 22px;
    position: relative; overflow: hidden;
  }
  .card-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px;
  }
  .card-title { font-size: 14px; font-weight: 700; }
  .card-title span { color: var(--neon); }
  .card-sub { font-size: 11px; color: var(--text-mid); margin-top: 2px; font-family: 'DM Mono', monospace; }
  .pill {
    font-size: 10px; padding: 3px 10px; border-radius: 20px;
    font-family: 'DM Mono', monospace; font-weight: 500;
    cursor: pointer; transition: all .2s;
  }
  .pill.active { background: rgba(0,200,255,0.15); color: var(--neon); border: 1px solid rgba(0,200,255,0.3); }
  .pill.inactive { background: rgba(255,255,255,0.05); color: var(--text-mid); border: 1px solid var(--border); }
  .pills { display: flex; gap: 6px; }

  .machine-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
  }
  .machine {
    border-radius: 10px;
    padding: 12px 10px;
    display: flex; flex-direction: column; align-items: center; gap: 7px;
    border: 1px solid transparent;
    transition: all .2s; cursor: pointer;
    position: relative;
  }
  .machine:hover { transform: scale(1.04); }
  .machine.online  {
    background: rgba(0,200,255,0.07);
    border-color: rgba(0,200,255,0.25);
  }
  .machine.busy {
    background: rgba(255,180,0,0.07);
    border-color: rgba(255,180,0,0.3);
  }
  .machine.offline {
    background: rgba(255,255,255,0.03);
    border-color: rgba(255,255,255,0.08);
  }
  .machine.error {
    background: rgba(255,59,107,0.07);
    border-color: rgba(255,59,107,0.3);
  }
  .machine-icon { font-size: 22px; }
  .machine-id {
    font-size: 10px; font-family: 'DM Mono', monospace; font-weight: 500;
    color: var(--text-mid);
  }
  .machine-status {
    font-size: 9px; font-family: 'DM Mono', monospace;
    padding: 2px 7px; border-radius: 10px; font-weight: 600;
  }
  .machine.online  .machine-status { background: rgba(0,200,255,0.2); color: var(--neon); }
  .machine.busy    .machine-status { background: rgba(255,180,0,0.2); color: var(--accent-warn); }
  .machine.offline .machine-status { background: rgba(255,255,255,0.06); color: var(--text-lo); }
  .machine.error   .machine-status { background: rgba(255,59,107,0.2); color: var(--accent-red); }
  .machine-time {
    font-size: 11px; font-weight: 700;
    font-family: 'DM Mono', monospace;
  }
  .machine.online  .machine-time { color: var(--neon); }
  .machine.busy    .machine-time { color: var(--accent-warn); }
  .machine.offline .machine-time { color: var(--text-lo); }
  .machine.error   .machine-time { color: var(--accent-red); }

  /* Activity Feed */
  .activity-feed { display: flex; flex-direction: column; gap: 12px; }
  .act-item {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 12px;
    background: rgba(0,200,255,0.04);
    border: 1px solid var(--border);
    border-radius: 10px;
    transition: border-color .2s;
  }
  .act-item:hover { border-color: rgba(0,200,255,0.25); }
  .act-dot {
    width: 8px; height: 8px; border-radius: 50%;
    margin-top: 5px; flex-shrink: 0;
  }
  .act-content { flex: 1; }
  .act-title { font-size: 12px; font-weight: 600; color: var(--text-hi); margin-bottom: 2px; }
  .act-sub   { font-size: 11px; color: var(--text-mid); font-family: 'DM Mono', monospace; }
  .act-time  { font-size: 10px; color: var(--text-lo); font-family: 'DM Mono', monospace; white-space: nowrap; }

  /* ── Bottom row ── */
  .bot-row {
    display: grid;
    grid-template-columns: 1fr 1fr 320px;
    gap: 16px;
  }

  /* Transactions table */
  .txn-table { width: 100%; border-collapse: collapse; }
  .txn-table th {
    text-align: left; font-size: 10px; letter-spacing: 1.5px;
    color: var(--text-lo); font-family: 'DM Mono', monospace;
    padding: 0 12px 12px; text-transform: uppercase; font-weight: 500;
  }
  .txn-table td {
    padding: 10px 12px; font-size: 12px;
    border-top: 1px solid rgba(255,255,255,0.04);
  }
  .txn-table tr:hover td { background: rgba(0,200,255,0.04); }
  .txn-machine { font-family: 'DM Mono', monospace; color: var(--neon); font-size: 11px; }
  .txn-user { color: var(--text-hi); font-weight: 500; }
  .txn-amount { font-family: 'DM Mono', monospace; font-weight: 700; color: var(--accent-grn); }
  .txn-dur { font-family: 'DM Mono', monospace; color: var(--text-mid); font-size: 11px; }
  .txn-badge {
    font-size: 10px; padding: 2px 8px; border-radius: 20px;
    font-family: 'DM Mono', monospace;
  }
  .badge-paid { background: rgba(0,230,118,0.15); color: var(--accent-grn); }
  .badge-pend { background: rgba(255,180,0,0.15); color: var(--accent-warn); }

  /* Revenue chart bars */
  .bar-chart {
    display: flex; align-items: flex-end; gap: 6px;
    height: 120px; padding-bottom: 4px;
  }
  .bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; }
  .bar-col   { flex: 1; width: 100%; display: flex; align-items: flex-end; }
  .bar {
    width: 100%; border-radius: 5px 5px 0 0;
    transition: height .3s ease;
    position: relative; cursor: pointer;
    min-height: 4px;
  }
  .bar.main-bar {
    background: linear-gradient(180deg, var(--neon), var(--neon2));
    box-shadow: 0 0 10px rgba(0,200,255,0.3);
  }
  .bar.comp-bar {
    background: rgba(0,200,255,0.15);
  }
  .bar-label { font-size: 9px; color: var(--text-lo); font-family: 'DM Mono', monospace; }
  .chart-legend { display: flex; gap: 14px; margin-bottom: 14px; }
  .legend-item { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-mid); }
  .legend-dot { width: 8px; height: 8px; border-radius: 2px; }

  /* Donut chart */
  .donut-wrap { display: flex; flex-direction: column; align-items: center; gap: 16px; }
  .donut-svg { filter: drop-shadow(0 0 12px rgba(0,200,255,0.3)); }
  .donut-center { text-anchor: middle; dominant-baseline: middle; }
  .donut-legend { width: 100%; display: flex; flex-direction: column; gap: 8px; }
  .dl-item {
    display: flex; align-items: center; gap: 10px;
    font-size: 12px;
  }
  .dl-bar-wrap { flex: 1; background: rgba(255,255,255,0.06); border-radius: 4px; height: 4px; overflow: hidden; }
  .dl-bar { height: 100%; border-radius: 4px; }
  .dl-name { color: var(--text-mid); width: 60px; font-size: 11px; }
  .dl-pct  { color: var(--text-hi); font-family: 'DM Mono', monospace; font-size: 11px; width: 36px; text-align: right; }

  /* Responsive scroll on small viewport */
  @media (max-width: 1200px) {
    .stats-row { grid-template-columns: repeat(2,1fr); }
    .mid-row   { grid-template-columns: 1fr; }
    .bot-row   { grid-template-columns: 1fr; }
    .machine-grid { grid-template-columns: repeat(4,1fr); }
  }

  /* Scrollbar */
  ::-webkit-scrollbar { width: 5px; height: 5px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: rgba(0,200,255,0.2); border-radius: 10px; }

  /* animate-in */
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .stat-card  { animation: fadeUp .4s ease both; }
  .stat-card:nth-child(1) { animation-delay:.05s; }
  .stat-card:nth-child(2) { animation-delay:.12s; }
  .stat-card:nth-child(3) { animation-delay:.19s; }
  .stat-card:nth-child(4) { animation-delay:.26s; }
  .glass-card { animation: fadeUp .5s ease .3s both; }
</style>
@yield('extra-styles')
</head>
<body>

<!-- ══ SIDEBAR ══ -->
@include('partials.sidebar')

<!-- ══ TOPBAR ══ -->
<header class="topbar">
  <div class="topbar-title">
    @yield('title', 'Dashboard') <span>Overview</span>
  </div>
  <div class="live-dot">LIVE</div>
  <div class="search-bar">
    <span class="search-icon">🔍</span>
    <input type="text" placeholder="Search machines, users…">
  </div>
  <div class="topbar-actions">
    <div class="icon-btn">🔔<span class="notif-dot"></span></div>
    <div class="icon-btn">📥</div>
    <div class="top-avatar">JD</div>
  </div>
</header>

<!-- ══ MAIN ══ -->
<main class="main">
  <div class="page">
    @yield('content')
  </div>
</main>

@yield('scripts')
</body>
</html>

