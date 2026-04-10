<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class RatesController extends Controller
{
    public function index()
    {
        // Get coin rates
        $coin1Rate = Setting::get('coin_1_rate', '5');
        $coin5Rate = Setting::get('coin_5_rate', '25');
        $coin10Rate = Setting::get('coin_10_rate', '50');
        $coin20Rate = Setting::get('coin_20_rate', '100');
        
        // Get pricing mode
        $pricingMode = Setting::get('pricing_mode', 'minutes');
        $pricePerMinute = Setting::get('price_per_minute', '1.00');
        
        return view('admin.rates.index', compact(
            'coin1Rate',
            'coin5Rate',
            'coin10Rate',
            'coin20Rate',
            'pricingMode',
            'pricePerMinute'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'coin_1_rate' => 'required|numeric|min:0',
            'coin_5_rate' => 'required|numeric|min:0',
            'coin_10_rate' => 'required|numeric|min:0',
            'coin_20_rate' => 'required|numeric|min:0',
            'pricing_mode' => 'required|in:minutes,fixed',
            'price_per_minute' => 'required|numeric|min:0',
        ]);

        // Save coin rates
        Setting::set('coin_1_rate', $validated['coin_1_rate'], 'string', '₱1 coin = minutes');
        Setting::set('coin_5_rate', $validated['coin_5_rate'], 'string', '₱5 coin = minutes');
        Setting::set('coin_10_rate', $validated['coin_10_rate'], 'string', '₱10 coin = minutes');
        Setting::set('coin_20_rate', $validated['coin_20_rate'], 'string', '₱20 coin = minutes');
        
        // Save pricing settings
        Setting::set('pricing_mode', $validated['pricing_mode'], 'string', 'Pricing mode (minutes or fixed)');
        Setting::set('price_per_minute', $validated['price_per_minute'], 'string', 'Price per minute');

        return redirect()->route('rates.index')->with('success', 'Rates updated successfully!');
    }
}
