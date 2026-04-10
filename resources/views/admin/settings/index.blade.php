@extends('layouts.admin')

@section('title', 'Server Settings')

@section('content')
<div class="settings-container">
    <div class="page-header">
        <div>
            <div class="page-title">Server <span>Settings</span></div>
            <div class="page-subtitle">Configure network and server settings</div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <span>✓</span> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <span>✕</span> {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <!-- Network Mode Selection -->
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="card-title">Network <span>Configuration</span></div>
                    <div class="card-sub">Choose how IP addresses are assigned to computers</div>
                </div>
            </div>

            <div class="card-body">
                <div class="network-mode-selector">
                    <label class="mode-option">
                        <input type="radio" name="network_mode" value="dhcp" {{ $networkMode === 'dhcp' ? 'checked' : '' }} onchange="toggleNetworkMode()">
                        <div class="mode-card">
                            <div class="mode-icon">🔄</div>
                            <div class="mode-title">DHCP (Dynamic)</div>
                            <div class="mode-desc">Automatically assigns IP addresses to computers. Easier to manage but IPs may change.</div>
                            <div class="mode-badge">Recommended for beginners</div>
                        </div>
                    </label>

                    <label class="mode-option">
                        <input type="radio" name="network_mode" value="static" {{ $networkMode === 'static' ? 'checked' : '' }} onchange="toggleNetworkMode()">
                        <div class="mode-card">
                            <div class="mode-icon">📌</div>
                            <div class="mode-title">Static IP</div>
                            <div class="mode-desc">Manually assign fixed IP addresses to each computer. More stable and reliable.</div>
                            <div class="mode-badge">Recommended for production</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- DHCP Settings -->
        <div class="glass-card" id="dhcpSettings" style="margin-top: 20px; display: {{ $networkMode === 'dhcp' ? 'block' : 'none' }};">
            <div class="card-header">
                <div>
                    <div class="card-title">DHCP <span>Settings</span></div>
                    <div class="card-sub">Configure automatic IP address assignment</div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">DHCP Range Start</label>
                        <input type="text" name="dhcp_range_start" class="form-input" value="{{ $dhcpRangeStart }}" placeholder="192.168.1.100">
                        <small class="form-hint">Starting IP address for DHCP pool</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">DHCP Range End</label>
                        <input type="text" name="dhcp_range_end" class="form-input" value="{{ $dhcpRangeEnd }}" placeholder="192.168.1.200">
                        <small class="form-hint">Ending IP address for DHCP pool</small>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">ℹ️</div>
                    <div class="info-content">
                        <div class="info-title">DHCP Mode Information</div>
                        <div class="info-text">
                            In DHCP mode, computers will automatically receive IP addresses from the specified range. 
                            Make sure your router/DHCP server is configured to assign IPs within this range.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static IP Settings -->
        <div class="glass-card" id="staticSettings" style="margin-top: 20px; display: {{ $networkMode === 'static' ? 'block' : 'none' }};">
            <div class="card-header">
                <div>
                    <div class="card-title">Static IP <span>Settings</span></div>
                    <div class="card-sub">Configure server network settings</div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Server IP Address <span class="required">*</span></label>
                        <input type="text" name="server_ip" class="form-input" value="{{ $serverIp }}" placeholder="192.168.1.100">
                        <small class="form-hint">The static IP address of this server</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subnet Mask</label>
                        <input type="text" name="subnet_mask" class="form-input" value="{{ $subnetMask }}" placeholder="255.255.255.0">
                        <small class="form-hint">Network subnet mask</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Gateway</label>
                        <input type="text" name="gateway" class="form-input" value="{{ $gateway }}" placeholder="192.168.1.1">
                        <small class="form-hint">Default gateway (usually your router)</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">DNS Server</label>
                        <input type="text" name="dns_server" class="form-input" value="{{ $dnsServer }}" placeholder="8.8.8.8">
                        <small class="form-hint">DNS server for name resolution</small>
                    </div>
                </div>

                <div class="info-box warning">
                    <div class="info-icon">⚠️</div>
                    <div class="info-content">
                        <div class="info-title">Static IP Mode Information</div>
                        <div class="info-text">
                            In Static IP mode, you must manually configure each computer's IP address. 
                            Each computer should have a unique IP in the same subnet as the server.
                            <br><br>
                            <strong>Example Configuration:</strong><br>
                            Server: {{ $serverIp ?: '192.168.1.100' }}<br>
                            PC-01: 192.168.1.101<br>
                            PC-02: 192.168.1.102<br>
                            PC-03: 192.168.1.103
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hardware Configuration -->
        <div class="glass-card" style="margin-top: 20px;">
            <div class="card-header">
                <div>
                    <div class="card-title">Hardware <span>Configuration</span></div>
                    <div class="card-sub">Configure GPIO pins for coin acceptor and buttons</div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Coin Pin (GPIO)</label>
                        <input type="text" name="coin_pin" class="form-input" value="{{ $coinPin }}" placeholder="Default: 11">
                        <small class="form-hint">GPIO pin connected to coin acceptor (Default: Pin 11)</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Set Pin (GPIO)</label>
                        <input type="text" name="set_pin" class="form-input" value="{{ $setPin }}" placeholder="Default: 12">
                        <small class="form-hint">GPIO pin connected to set button (Default: Pin 12)</small>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">🔌</div>
                    <div class="info-content">
                        <div class="info-title">Hardware Pin Configuration</div>
                        <div class="info-text">
                            Configure the GPIO pins used by your hardware devices (coin acceptor, buttons, etc.).
                            These pins are used by the client software running on each computer.
                            <br><br>
                            <strong>Default GPIO Pins:</strong><br>
                            Coin Acceptor: Pin 11<br>
                            Set Button: Pin 12
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="form-actions">
            <button type="submit" class="btn-save">
                💾 Save Settings
            </button>
            <a href="{{ route('dashboard') }}" class="btn-cancel">
                Cancel
            </a>
        </div>
    </form>

    <!-- Backup & Restore Section -->
    <div class="glass-card" style="margin-top: 20px;">
        <div class="card-header">
            <div>
                <div class="card-title">Backup & <span>Restore</span></div>
                <div class="card-sub">Backup your settings or restore from a previous backup</div>
            </div>
        </div>

        <div class="card-body">
            <div class="backup-restore-grid">
                <!-- Backup Section -->
                <div class="backup-section">
                    <div class="section-icon">💾</div>
                    <div class="section-title">Backup Settings</div>
                    <div class="section-desc">Download a backup file containing all your server settings and data</div>
                    <form method="POST" action="{{ route('settings.backup') }}" style="margin-top: 16px;">
                        @csrf
                        <button type="submit" class="btn-backup">
                            📥 Download Backup
                        </button>
                    </form>
                </div>

                <!-- Restore Section -->
                <div class="restore-section">
                    <div class="section-icon">♻️</div>
                    <div class="section-title">Restore Settings</div>
                    <div class="section-desc">Upload a backup file to restore your settings</div>
                    <form method="POST" action="{{ route('settings.restore') }}" enctype="multipart/form-data" style="margin-top: 16px;">
                        @csrf
                        <input type="file" name="backup_file" id="backupFile" accept=".json" style="display: none;" onchange="this.form.submit()">
                        <button type="button" class="btn-restore" onclick="document.getElementById('backupFile').click()">
                            📤 Upload Backup
                        </button>
                    </form>
                </div>
            </div>

            <div class="info-box warning" style="margin-top: 20px;">
                <div class="info-icon">⚠️</div>
                <div class="info-content">
                    <div class="info-title">Important Backup Information</div>
                    <div class="info-text">
                        <strong>Backup includes:</strong> All settings, user accounts, PC units, transactions, coin inserts, and sessions.<br>
                        <strong>Restore will:</strong> Overwrite current settings with the backup data.<br>
                        <strong>Recommendation:</strong> Create regular backups before making major changes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 4px;
}

.page-title span {
    color: var(--neon);
}

.page-subtitle {
    font-size: 13px;
    color: var(--text-mid);
}

.glass-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    backdrop-filter: blur(16px);
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 4px;
}

.card-title span {
    color: var(--neon);
}

.card-sub {
    font-size: 12px;
    color: var(--text-mid);
}

.card-body {
    padding: 24px;
}

.network-mode-selector {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.mode-option {
    cursor: pointer;
}

.mode-option input[type="radio"] {
    display: none;
}

.mode-card {
    padding: 24px;
    background: var(--bg-deep);
    border: 2px solid var(--border);
    border-radius: 12px;
    transition: all .3s ease;
    height: 100%;
}

.mode-option input[type="radio"]:checked + .mode-card {
    border-color: var(--neon);
    background: rgba(0,200,255,0.08);
    box-shadow: 0 0 20px rgba(0,200,255,0.2);
}

.mode-icon {
    font-size: 40px;
    margin-bottom: 16px;
}

.mode-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 8px;
}

.mode-desc {
    font-size: 13px;
    color: var(--text-mid);
    line-height: 1.6;
    margin-bottom: 12px;
}

.mode-badge {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(0,230,118,0.15);
    border: 1px solid rgba(0,230,118,0.3);
    border-radius: 20px;
    font-size: 11px;
    color: var(--accent-grn);
    font-family: 'DM Mono', monospace;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-mid);
    margin-bottom: 8px;
    font-family: 'DM Mono', monospace;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.required {
    color: var(--accent-red);
}

.form-input {
    padding: 12px 16px;
    background: var(--bg-deep);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-hi);
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    outline: none;
    transition: border-color .2s;
}

.form-input:focus {
    border-color: var(--neon);
    box-shadow: 0 0 12px rgba(0,200,255,0.2);
}

.form-hint {
    font-size: 11px;
    color: var(--text-lo);
    margin-top: 6px;
    font-family: 'DM Mono', monospace;
}

.info-box {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: rgba(0,200,255,0.08);
    border: 1px solid rgba(0,200,255,0.2);
    border-radius: 10px;
    margin-top: 20px;
}

.info-box.warning {
    background: rgba(255,180,0,0.08);
    border-color: rgba(255,180,0,0.2);
}

.info-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 8px;
}

.info-text {
    font-size: 12px;
    color: var(--text-mid);
    line-height: 1.6;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 32px;
}

.btn-save {
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border: none;
    border-radius: 10px;
    color: var(--bg-deep);
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
    box-shadow: var(--neon-glow);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 24px rgba(0,200,255,0.5);
}

.btn-cancel {
    padding: 14px 32px;
    background: transparent;
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-mid);
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all .2s ease;
}

.btn-cancel:hover {
    border-color: var(--neon);
    color: var(--neon);
}

.backup-restore-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.backup-section,
.restore-section {
    padding: 24px;
    background: var(--bg-deep);
    border: 1px solid var(--border);
    border-radius: 12px;
    text-align: center;
}

.section-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.section-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 8px;
}

.section-desc {
    font-size: 12px;
    color: var(--text-mid);
    line-height: 1.6;
}

.btn-backup,
.btn-restore {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
    width: 100%;
}

.btn-backup {
    background: linear-gradient(135deg, #00e676, #00c853);
    color: var(--bg-deep);
    box-shadow: 0 4px 15px rgba(0,230,118,0.3);
}

.btn-backup:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,230,118,0.4);
}

.btn-restore {
    background: linear-gradient(135deg, #ffb400, #ff9100);
    color: var(--bg-deep);
    box-shadow: 0 4px 15px rgba(255,180,0,0.3);
}

.btn-restore:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,180,0,0.4);
}

.alert {
    padding: 14px 20px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: rgba(0,230,118,0.12);
    color: var(--accent-grn);
    border: 1px solid rgba(0,230,118,0.25);
}

.alert-error {
    background: rgba(255,59,107,0.12);
    color: var(--accent-red);
    border: 1px solid rgba(255,59,107,0.25);
}

@media (max-width: 768px) {
    .network-mode-selector,
    .form-row {
        grid-template-columns: 1fr;
    }
    .backup-restore-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function toggleNetworkMode() {
    const mode = document.querySelector('input[name="network_mode"]:checked').value;
    const dhcpSettings = document.getElementById('dhcpSettings');
    const staticSettings = document.getElementById('staticSettings');
    
    if (mode === 'dhcp') {
        dhcpSettings.style.display = 'block';
        staticSettings.style.display = 'none';
    } else {
        dhcpSettings.style.display = 'none';
        staticSettings.style.display = 'block';
    }
}
</script>
@endsection
