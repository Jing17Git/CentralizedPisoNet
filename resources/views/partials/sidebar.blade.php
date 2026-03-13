<!-- ══ SIDEBAR ══ -->
<nav class="sidebar">
  <div class="brand">
    <div class="brand-icon">🖥</div>
    <div class="brand-text">
      <div class="brand-name">PisoNet</div>
      <div class="brand-sub">CENTRAL</div>
    </div>
  </div>

    <div class="nav-section">
    <div class="nav-label">Overview</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
      <span class="nav-icon">⬛</span> Dashboard
    </a>
    <a href="{{ route('pcunits.index') }}" class="nav-item {{ Route::is('pcunits.index') ? 'active' : '' }}">
      <span class="nav-icon">🖥</span> PC Units
    </a>
    <a href="{{ route('sessions.index') }}" class="nav-item {{ Route::is('sessions.index') ? 'active' : '' }}">
      <span class="nav-icon">👥</span> Sessions
    </a>
    
  </div>

    <div class="nav-section">
    <div class="nav-label">Finance</div>
    <a href="{{ route('transactions.index') }}" class="nav-item {{ Route::is('transactions.index') ? 'active' : '' }}">
      <span class="nav-icon">💳</span> Transactions
    </a>
    <a href="{{ route('earnings.index') }}" class="nav-item">
      <span class="nav-icon">💰</span> Earnings
    </a>
    <a href="{{ route('coininserts.index') }}" class="nav-item {{ Route::is('coininserts.index') ? 'active' : '' }}">
      <span class="nav-icon">🪙</span> Coin Inserts
    </a>
    <a href="{{ route('reports.index') }}" class="nav-item {{ Route::is('reports.*') ? 'active' : '' }}">
      <span class="nav-icon">📊</span> Reports
    </a>
  </div>

  

  <div class="sidebar-footer">
    <div class="user-chip">
      <div class="user-avatar"></div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role">{{ strtoupper(auth()->user()->role ?? 'ADMIN') }}</div>
      </div>
      <a href="{{ route('admin.profile.show') }}" style="color:var(--text-lo);font-size:14px;text-decoration:none;" title="View Profile">⚙️</a>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-top: 8px;">
      @csrf
      <button type="submit" class="nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
        <span class="nav-icon">🚪</span> Logout
      </button>
    </form>
  </div>
</nav>

