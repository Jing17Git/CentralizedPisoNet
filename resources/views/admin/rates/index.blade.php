@extends('layouts.app')
@section('title', 'Rates')
@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px;">

    <div class="card">
        <div class="card-header">
            <h3>⬡ Coin Denomination → Minutes</h3>
        </div>
        <p style="color:var(--text-muted);font-size:12px;margin-bottom:18px;line-height:1.6;">
            Set how many minutes each coin denomination gives. Applied automatically by the coin slot listener.
        </p>

        <form method="POST" action="{{ route('rates.update') }}">
            @csrf

            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom:12px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="form-row" style="margin-bottom:0;">
                <div>
                    <label for="coin_1_rate">₱1 Coin — Minutes</label>
                    <input type="number" id="coin_1_rate" name="coin_1_rate" value="{{ old('coin_1_rate', $coin1Rate) }}" min="1" step="1" required>

                    <label for="coin_5_rate">₱5 Coin — Minutes</label>
                    <input type="number" id="coin_5_rate" name="coin_5_rate" value="{{ old('coin_5_rate', $coin5Rate) }}" min="1" step="1" required>
                </div>
                <div>
                    <label for="coin_10_rate">₱10 Coin — Minutes</label>
                    <input type="number" id="coin_10_rate" name="coin_10_rate" value="{{ old('coin_10_rate', $coin10Rate) }}" min="1" step="1" required>

                    <label for="coin_20_rate">₱20 Coin — Minutes</label>
                    <input type="number" id="coin_20_rate" name="coin_20_rate" value="{{ old('coin_20_rate', $coin20Rate) }}" min="1" step="1" required>
                </div>
            </div>

            <div style="border-top:1px solid var(--border);margin:16px 0;"></div>

            <label for="pricing_mode">Pricing Mode</label>
            <select id="pricing_mode" name="pricing_mode">
                <option value="minutes" {{ $pricingMode === 'minutes' ? 'selected' : '' }}>Per Minute (coin = minutes)</option>
                <option value="fixed"   {{ $pricingMode === 'fixed'   ? 'selected' : '' }}>Fixed Rate (₱ per minute)</option>
            </select>

            <label for="price_per_minute">Price Per Minute (₱)</label>
            <input type="number" id="price_per_minute" name="price_per_minute" value="{{ old('price_per_minute', $pricePerMinute) }}" min="0.01" step="0.01" required>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:10px;" id="save-rates-btn">
                Save Rates
            </button>
        </form>
    </div>

    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3>◲ Live Preview</h3>
            </div>
            <table>
                <thead><tr><th>Coin</th><th>Minutes Given</th></tr></thead>
                <tbody>
                    <tr><td>₱1</td><td><span id="prev1">{{ $coin1Rate }}</span> min</td></tr>
                    <tr><td>₱5</td><td><span id="prev5">{{ $coin5Rate }}</span> min</td></tr>
                    <tr><td>₱10</td><td><span id="prev10">{{ $coin10Rate }}</span> min</td></tr>
                    <tr><td>₱20</td><td><span id="prev20">{{ $coin20Rate }}</span> min</td></tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>◈ Current Active Rates</h3>
            </div>
            <table>
                <thead><tr><th>Coin</th><th>Minutes</th><th>Peso Value</th></tr></thead>
                <tbody>
                    <tr><td>₱1</td><td>{{ $coin1Rate }} min</td><td>₱1.00</td></tr>
                    <tr><td>₱5</td><td>{{ $coin5Rate }} min</td><td>₱5.00</td></tr>
                    <tr><td>₱10</td><td>{{ $coin10Rate }} min</td><td>₱10.00</td></tr>
                    <tr><td>₱20</td><td>{{ $coin20Rate }} min</td><td>₱20.00</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    ['1','5','10','20'].forEach(d => {
        const input = document.getElementById('coin_' + d + '_rate');
        const span  = document.getElementById('prev' + d);
        if (input && span) {
            input.addEventListener('input', () => {
                span.textContent = input.value || 0;
            });
        }
    });
</script>
@endsection
