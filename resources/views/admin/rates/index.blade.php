@extends('layouts.app')
@section('title', 'Rates')
@section('content')

<div class="card" style="max-width:600px;">
    <h3>💲 Coin Rates & Pricing</h3>
    <p style="color:#aaa;font-size:13px;margin-bottom:20px;">
        Set how many minutes each coin denomination gives. These are used by the coin slot listener automatically.
    </p>

    <form method="POST" action="{{ route('rates.update') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:20px;">

            {{-- Coin Rates --}}
            <div>
                <div style="color:#00c8ff;font-size:13px;font-weight:bold;margin-bottom:12px;">🪙 Coin Denomination → Minutes</div>

                <label>₱1 Coin = how many minutes</label>
                <input type="number" name="coin_1_rate" value="{{ old('coin_1_rate', $coin1Rate) }}" min="1" step="1" required>

                <label>₱5 Coin = how many minutes</label>
                <input type="number" name="coin_5_rate" value="{{ old('coin_5_rate', $coin5Rate) }}" min="1" step="1" required>

                <label>₱10 Coin = how many minutes</label>
                <input type="number" name="coin_10_rate" value="{{ old('coin_10_rate', $coin10Rate) }}" min="1" step="1" required>

                <label>₱20 Coin = how many minutes</label>
                <input type="number" name="coin_20_rate" value="{{ old('coin_20_rate', $coin20Rate) }}" min="1" step="1" required>
            </div>

            {{-- Pricing Settings --}}
            <div>
                <div style="color:#00c8ff;font-size:13px;font-weight:bold;margin-bottom:12px;">⚙️ Pricing Settings</div>

                <label>Pricing Mode</label>
                <select name="pricing_mode">
                    <option value="minutes" {{ $pricingMode === 'minutes' ? 'selected' : '' }}>Per Minute (coin = minutes)</option>
                    <option value="fixed"   {{ $pricingMode === 'fixed'   ? 'selected' : '' }}>Fixed Rate (₱ per minute)</option>
                </select>

                <label>Price Per Minute (₱)</label>
                <input type="number" name="price_per_minute" value="{{ old('price_per_minute', $pricePerMinute) }}" min="0.01" step="0.01" required>

                {{-- Preview --}}
                <div style="background:#0f3460;border-radius:6px;padding:12px;margin-top:8px;">
                    <div style="font-size:12px;color:#aaa;margin-bottom:8px;">Preview</div>
                    <div style="font-size:12px;color:#eee;">
                        ₱1 → <span id="prev1">{{ $coin1Rate }}</span> min<br>
                        ₱5 → <span id="prev5">{{ $coin5Rate }}</span> min<br>
                        ₱10 → <span id="prev10">{{ $coin10Rate }}</span> min<br>
                        ₱20 → <span id="prev20">{{ $coin20Rate }}</span> min
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:12px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:15px;">
            💾 Save Rates
        </button>
    </form>
</div>

{{-- Current rates summary --}}
<div class="card" style="max-width:600px;margin-top:20px;">
    <h3>📋 Current Active Rates</h3>
    <table>
        <thead>
            <tr>
                <th>Coin</th>
                <th>Minutes Given</th>
                <th>Peso Value</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>₱1</td><td>{{ $coin1Rate }} min</td><td>₱1.00</td></tr>
            <tr><td>₱5</td><td>{{ $coin5Rate }} min</td><td>₱5.00</td></tr>
            <tr><td>₱10</td><td>{{ $coin10Rate }} min</td><td>₱10.00</td></tr>
            <tr><td>₱20</td><td>{{ $coin20Rate }} min</td><td>₱20.00</td></tr>
        </tbody>
    </table>
</div>

<script>
    // Live preview update
    ['1','5','10','20'].forEach(d => {
        const input = document.querySelector(`[name="coin_${d}_rate"]`);
        const span  = document.getElementById(`prev${d}`);
        if (input && span) {
            input.addEventListener('input', (e) => {
                e.preventDefault();
                span.textContent = input.value || 0;
            });
        }
    });
</script>
@endsection
