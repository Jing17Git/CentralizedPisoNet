@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<div class="card" style="max-width:800px;">
    <div class="card-header">
        <h3>⚙ Network & Hardware Settings</h3>
    </div>
    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-row">
            <div>
                <label for="network_mode">Network Mode</label>
                <select id="network_mode" name="network_mode">
                    <option value="dhcp"   {{ $networkMode === 'dhcp'   ? 'selected' : '' }}>DHCP</option>
                    <option value="static" {{ $networkMode === 'static' ? 'selected' : '' }}>Static</option>
                </select>

                <label for="server_ip">Server IP</label>
                <input type="text" id="server_ip" name="server_ip" value="{{ $serverIp }}">

                <label for="subnet_mask">Subnet Mask</label>
                <input type="text" id="subnet_mask" name="subnet_mask" value="{{ $subnetMask }}">

                <label for="gateway">Gateway</label>
                <input type="text" id="gateway" name="gateway" value="{{ $gateway }}">
            </div>
            <div>
                <label for="dns_server">DNS Server</label>
                <input type="text" id="dns_server" name="dns_server" value="{{ $dnsServer }}">

                <label for="dhcp_range_start">DHCP Range Start</label>
                <input type="text" id="dhcp_range_start" name="dhcp_range_start" value="{{ $dhcpRangeStart }}">

                <label for="dhcp_range_end">DHCP Range End</label>
                <input type="text" id="dhcp_range_end" name="dhcp_range_end" value="{{ $dhcpRangeEnd }}">

                <label for="coin_pin">Coin Pin</label>
                <input type="text" id="coin_pin" name="coin_pin" value="{{ $coinPin }}">

                <label for="set_pin">Set Pin</label>
                <input type="text" id="set_pin" name="set_pin" value="{{ $setPin }}">
            </div>
        </div>

        <div style="border-top:1px solid var(--border);margin:4px 0 16px;"></div>
        <button type="submit" class="btn btn-primary" id="save-settings-btn">Save Settings</button>
    </form>
</div>
@endsection
