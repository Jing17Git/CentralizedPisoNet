<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function index()
    {
        $networkMode = Setting::get('network_mode', 'dhcp');
        $serverIp = Setting::get('server_ip', '');
        $subnetMask = Setting::get('subnet_mask', '255.255.255.0');
        $gateway = Setting::get('gateway', '');
        $dnsServer = Setting::get('dns_server', '8.8.8.8');
        $dhcpRangeStart = Setting::get('dhcp_range_start', '192.168.1.100');
        $dhcpRangeEnd = Setting::get('dhcp_range_end', '192.168.1.200');
        $coinPin = Setting::get('coin_pin', '11');
        $setPin = Setting::get('set_pin', '12');
        
        return view('admin.settings.index', compact(
            'networkMode',
            'serverIp',
            'subnetMask',
            'gateway',
            'dnsServer',
            'dhcpRangeStart',
            'dhcpRangeEnd',
            'coinPin',
            'setPin'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'network_mode' => 'required|in:dhcp,static',
            'server_ip' => 'nullable|ip',
            'subnet_mask' => 'nullable|ip',
            'gateway' => 'nullable|ip',
            'dns_server' => 'nullable|ip',
            'dhcp_range_start' => 'nullable|ip',
            'dhcp_range_end' => 'nullable|ip',
            'coin_pin' => 'nullable|string|max:50',
            'set_pin' => 'nullable|string|max:50',
        ]);

        Setting::set('network_mode', $validated['network_mode'], 'string', 'Network configuration mode');
        
        if ($validated['network_mode'] === 'static') {
            Setting::set('server_ip', $validated['server_ip'] ?? '', 'string', 'Static server IP address');
            Setting::set('subnet_mask', $validated['subnet_mask'] ?? '255.255.255.0', 'string', 'Subnet mask');
            Setting::set('gateway', $validated['gateway'] ?? '', 'string', 'Default gateway');
            Setting::set('dns_server', $validated['dns_server'] ?? '8.8.8.8', 'string', 'DNS server');
        } else {
            Setting::set('dhcp_range_start', $validated['dhcp_range_start'] ?? '192.168.1.100', 'string', 'DHCP range start');
            Setting::set('dhcp_range_end', $validated['dhcp_range_end'] ?? '192.168.1.200', 'string', 'DHCP range end');
        }
        
        // Save hardware pins
        Setting::set('coin_pin', $validated['coin_pin'] ?? '', 'string', 'Coin acceptor GPIO pin');
        Setting::set('set_pin', $validated['set_pin'] ?? '', 'string', 'Set button GPIO pin');

        return redirect()->route('settings.index')->with('success', 'Server settings updated successfully!');
    }

    public function backup()
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $filename = "backup_{$timestamp}.json";
            
            // Backup all settings
            $settings = Setting::all()->toArray();
            
            // Backup database structure and data
            $backup = [
                'timestamp' => $timestamp,
                'settings' => $settings,
                'users' => DB::table('users')->get()->toArray(),
                'pc_units' => DB::table('pc_units')->get()->toArray(),
                'transactions' => DB::table('transactions')->get()->toArray(),
                'coin_inserts' => DB::table('coin_inserts')->get()->toArray(),
                'sessions' => DB::table('sessions')->get()->toArray(),
            ];
            
            // Save to storage
            Storage::disk('local')->put('backups/' . $filename, json_encode($backup, JSON_PRETTY_PRINT));
            
            // Download the backup file
            return Storage::disk('local')->download('backups/' . $filename);
            
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json',
        ]);
        
        try {
            $file = $request->file('backup_file');
            $content = file_get_contents($file->getRealPath());
            $backup = json_decode($content, true);
            
            if (!$backup || !isset($backup['settings'])) {
                return redirect()->route('settings.index')->with('error', 'Invalid backup file format!');
            }
            
            DB::beginTransaction();
            
            // Restore settings
            if (isset($backup['settings'])) {
                foreach ($backup['settings'] as $setting) {
                    Setting::updateOrCreate(
                        ['key' => $setting['key']],
                        [
                            'value' => $setting['value'],
                            'type' => $setting['type'],
                            'description' => $setting['description'] ?? null,
                        ]
                    );
                }
            }
            
            DB::commit();
            
            return redirect()->route('settings.index')->with('success', 'Settings restored successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.index')->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
}
