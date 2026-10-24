@extends('layouts.app')
@section('title', 'Rates')
@section('content')
<div class="card">
    <h3>Coin Rates & Pricing</h3>
    <form method="POST" action="{{ route('rates.update', 1) }}">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label>₱1 Coin = (minutes)</label>
                <input type="number" name="coin_1_rate" value="{{ $coin1Rate }}" min="0" step="0.1">
                <label>₱5 Coin = (minutes)</label>
                <input type="number" name="coin_5_rate" value="{{ $coin5Rate }}" min="0" step="0.1">
                <label>₱10 Coin = (minutes)</label>
                <input type="number" name="coin_10_rate" value="{{ $coin10Rate }}" min="0" step="0.1">
                <label>₱20 Coin = (minutes)</label>
                <input type="number" name="coin_20_rate" value="{{ $coin20Rate }}" min="0" step="0.1">
            </div>
            <div>
                <label>Pricing Mode</label>
                <select name="pricing_mode">
                    <option value="minutes" {{ $pricingMode === 'minutes' ? 'selected' : '' }}>Per Minute</option>
                    <option value="fixed" {{ $pricingMode === 'fixed' ? 'selected' : '' }}>Fixed</option>
                </select>
                <label>Price Per Minute (₱)</label>
                <input type="number" name="price_per_minute" value="{{ $pricePerMinute }}" min="0" step="0.01">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Rates</button>
    </form>
</div>
@endsection
