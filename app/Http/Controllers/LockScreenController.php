<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class LockScreenController extends Controller
{
    public function index()
    {
        $lockscreenTitle = Setting::get('lockscreen_title', 'CentralizedPisoNet');
        $lockscreenMessage = Setting::get('lockscreen_message', 'Insert coin to start');
        $lockscreenLogo = Setting::get('lockscreen_logo', '');
        $lockscreenBackground = Setting::get('lockscreen_background', '');
        $lockscreenTheme = Setting::get('lockscreen_theme', 'dark');
        $showTimer = Setting::get('lockscreen_show_timer', 'true');
        $showPrice = Setting::get('lockscreen_show_price', 'true');
        
        return view('admin.lockscreen.index', compact(
            'lockscreenTitle',
            'lockscreenMessage',
            'lockscreenLogo',
            'lockscreenBackground',
            'lockscreenTheme',
            'showTimer',
            'showPrice'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'lockscreen_title' => 'required|string|max:100',
            'lockscreen_message' => 'required|string|max:255',
            'lockscreen_theme' => 'required|in:dark,light,blue,green',
            'lockscreen_show_timer' => 'required|in:true,false',
            'lockscreen_show_price' => 'required|in:true,false',
            'lockscreen_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'lockscreen_background' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Save text settings
        Setting::set('lockscreen_title', $validated['lockscreen_title'], 'string', 'Lockscreen title');
        Setting::set('lockscreen_message', $validated['lockscreen_message'], 'string', 'Lockscreen message');
        Setting::set('lockscreen_theme', $validated['lockscreen_theme'], 'string', 'Lockscreen theme');
        Setting::set('lockscreen_show_timer', $validated['lockscreen_show_timer'], 'string', 'Show timer on lockscreen');
        Setting::set('lockscreen_show_price', $validated['lockscreen_show_price'], 'string', 'Show price on lockscreen');

        // Handle logo upload
        if ($request->hasFile('lockscreen_logo')) {
            $oldLogo = Setting::get('lockscreen_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $logoPath = $request->file('lockscreen_logo')->store('lockscreen', 'public');
            Setting::set('lockscreen_logo', $logoPath, 'string', 'Lockscreen logo path');
        }

        // Handle background upload
        if ($request->hasFile('lockscreen_background')) {
            $oldBackground = Setting::get('lockscreen_background');
            if ($oldBackground && Storage::disk('public')->exists($oldBackground)) {
                Storage::disk('public')->delete($oldBackground);
            }
            
            $backgroundPath = $request->file('lockscreen_background')->store('lockscreen', 'public');
            Setting::set('lockscreen_background', $backgroundPath, 'string', 'Lockscreen background path');
        }

        return redirect()->route('lockscreen.index')->with('success', 'Lockscreen settings updated successfully!');
    }

    public function preview()
    {
        $lockscreenTitle = Setting::get('lockscreen_title', 'CentralizedPisoNet');
        $lockscreenMessage = Setting::get('lockscreen_message', 'Insert coin to start');
        $lockscreenLogo = Setting::get('lockscreen_logo', '');
        $lockscreenBackground = Setting::get('lockscreen_background', '');
        $lockscreenTheme = Setting::get('lockscreen_theme', 'dark');
        $showTimer = Setting::get('lockscreen_show_timer', 'true');
        $showPrice = Setting::get('lockscreen_show_price', 'true');
        $pricePerMinute = Setting::get('price_per_minute', '1.00');
        
        return view('admin.lockscreen.preview', compact(
            'lockscreenTitle',
            'lockscreenMessage',
            'lockscreenLogo',
            'lockscreenBackground',
            'lockscreenTheme',
            'showTimer',
            'showPrice',
            'pricePerMinute'
        ));
    }
}
