<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class RatesController extends Controller
{
    public function index()
    {
        return view('admin.rates.index', [
            'coin1Rate'      => Setting::get('coin_1_rate',  '5'),
            'coin5Rate'      => Setting::get('coin_5_rate',  '25'),
            'coin10Rate'     => Setting::get('coin_10_rate', '50'),
            'coin20Rate'     => Setting::get('coin_20_rate', '100'),
            'pricingMode'    => Setting::get('pricing_mode', 'minutes'),
            'pricePerMinute' => Setting::get('price_per_minute', '1.00'),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'coin_1_rate'      => 'required|integer|min:1',
            'coin_5_rate'      => 'required|integer|min:1',
            'coin_10_rate'     => 'required|integer|min:1',
            'coin_20_rate'     => 'required|integer|min:1',
            'pricing_mode'     => 'required|in:minutes,fixed',
            'price_per_minute' => 'required|numeric|min:0.01',
        ]);

        Setting::set('coin_1_rate',      $validated['coin_1_rate'],      'integer', '₱1 coin = minutes');
        Setting::set('coin_5_rate',      $validated['coin_5_rate'],      'integer', '₱5 coin = minutes');
        Setting::set('coin_10_rate',     $validated['coin_10_rate'],     'integer', '₱10 coin = minutes');
        Setting::set('coin_20_rate',     $validated['coin_20_rate'],     'integer', '₱20 coin = minutes');
        Setting::set('pricing_mode',     $validated['pricing_mode'],     'string',  'Pricing mode');
        Setting::set('price_per_minute', $validated['price_per_minute'], 'decimal', 'Price per minute');

        return redirect()->route('rates.index')->with('success', 'Rates updated successfully!');
    }

    // Called by coin_listener.py to get current rates
    public function api()
    {
        return response()->json([
            'success' => true,
            // key = pulse count (= peso denomination), value = minutes
            'rates' => [
                '1'  => (int) Setting::get('coin_1_rate',  5),
                '5'  => (int) Setting::get('coin_5_rate',  25),
                '10' => (int) Setting::get('coin_10_rate', 50),
                '20' => (int) Setting::get('coin_20_rate', 100),
            ],
            'pricing_mode'     => Setting::get('pricing_mode', 'minutes'),
            'price_per_minute' => (float) Setting::get('price_per_minute', 1.00),
        ]);
    }
}
