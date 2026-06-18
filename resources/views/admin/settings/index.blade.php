@extends('layouts.app')
@section('title', 'Settings')
@section('content')
<div class="card">
    <h3>Network & Hardware Settings</h3>
    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label>Network Mode</label>
                <select name="network_mode">
                    <option value="dhcp" {{ $networkMode === 'dhcp' ? 'selected' : '' }}>DHCP</option>
                    <option value="static" {{ $networkMode === 'static' ? 'selected' : '' }}>Static</option>
                </select>
                <label>Server IP</label>
                <input type="text" name="server_ip" value="{{ $serverIp }}">
                <label>Subnet Mask</label>
                <input type="text" name="subnet_mask" value="{{ $subnetMask }}">
                <label>Gateway</label>
                <input type="text" name="gateway" value="{{ $gateway }}">
            </div>
            <div>
                <label>DNS Server</label>
                <input type="text" name="dns_server" value="{{ $dnsServer }}">
                <label>DHCP Range Start</label>
                <input type="text" name="dhcp_range_start" value="{{ $dhcpRangeStart }}">
                <label>DHCP Range End</label>
                <input type="text" name="dhcp_range_end" value="{{ $dhcpRangeEnd }}">
                <label>Coin Pin</label>
                <input type="text" name="coin_pin" value="{{ $coinPin }}">
                <label>Set Pin</label>
                <input type="text" name="set_pin" value="{{ $setPin }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
