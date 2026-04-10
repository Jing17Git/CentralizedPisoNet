@extends('layouts.admin')

@section('title', 'Rates Management')

@section('content')
<div class="rates-container">
    <div class="page-header">
        <div>
            <div class="page-title">Rates <span>Management</span></div>
            <div class="page-subtitle">Configure coin rates and pricing for your PisoNet system</div>
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

    <form method="POST" action="{{ route('rates.update') }}">
        @csrf
        @method('PUT')

        <!-- Coin Rates Configuration -->
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="card-title">Coin <span>Rates</span></div>
                    <div class="card-sub">Set how many minutes each coin denomination provides</div>
                </div>
            </div>

            <div class="card-body">
                <div class="rates-grid">
                    <!-- 1 Peso Coin -->
                    <div class="rate-card">
                        <div class="coin-icon">🪙</div>
                        <div class="coin-label">₱1 Coin</div>
                        <div class="rate-input-group">
                            <input type="number" name="coin_1_rate" class="rate-input" value="{{ $coin1Rate }}" min="0" step="1" required>
                            <span class="rate-unit">minutes</span>
                        </div>
                        <div class="rate-hint">1 peso = <span id="coin1Display">{{ $coin1Rate }}</span> min</div>
                    </div>

                    <!-- 5 Peso Coin -->
                    <div class="rate-card">
                        <div class="coin-icon">🪙</div>
                        <div class="coin-label">₱5 Coin</div>
                        <div class="rate-input-group">
                            <input type="number" name="coin_5_rate" class="rate-input" value="{{ $coin5Rate }}" min="0" step="1" required>
                            <span class="rate-unit">minutes</span>
                        </div>
                        <div class="rate-hint">5 pesos = <span id="coin5Display">{{ $coin5Rate }}</span> min</div>
                    </div>

                    <!-- 10 Peso Coin -->
                    <div class="rate-card">
                        <div class="coin-icon">🪙</div>
                        <div class="coin-label">₱10 Coin</div>
                        <div class="rate-input-group">
                            <input type="number" name="coin_10_rate" class="rate-input" value="{{ $coin10Rate }}" min="0" step="1" required>
                            <span class="rate-unit">minutes</span>
                        </div>
                        <div class="rate-hint">10 pesos = <span id="coin10Display">{{ $coin10Rate }}</span> min</div>
                    </div>

                    <!-- 20 Peso Coin -->
                    <div class="rate-card">
                        <div class="coin-icon">🪙</div>
                        <div class="coin-label">₱20 Coin</div>
                        <div class="rate-input-group">
                            <input type="number" name="coin_20_rate" class="rate-input" value="{{ $coin20Rate }}" min="0" step="1" required>
                            <span class="rate-unit">minutes</span>
                        </div>
                        <div class="rate-hint">20 pesos = <span id="coin20Display">{{ $coin20Rate }}</span> min</div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">💡</div>
                    <div class="info-content">
                        <div class="info-title">Coin Rate Configuration</div>
                        <div class="info-text">
                            Set how many minutes of computer time each coin denomination provides. 
                            These rates will be used by the coin acceptor system to calculate session time.
                            <br><br>
                            <strong>Example:</strong> If ₱5 = 25 minutes, inserting a ₱5 coin gives the user 25 minutes of computer time.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Configuration -->
        <div class="glass-card" style="margin-top: 20px;">
            <div class="card-header">
                <div>
                    <div class="card-title">Pricing <span>Configuration</span></div>
                    <div class="card-sub">Set pricing mode and rate per minute</div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Pricing Mode</label>
                    <div class="pricing-mode-selector">
                        <label class="mode-option">
                            <input type="radio" name="pricing_mode" value="minutes" {{ $pricingMode === 'minutes' ? 'checked' : '' }}>
                            <div class="mode-card">
                                <div class="mode-icon">⏱️</div>
                                <div class="mode-title">Time-Based</div>
                                <div class="mode-desc">Charge per minute of usage</div>
                            </div>
                        </label>

                        <label class="mode-option">
                            <input type="radio" name="pricing_mode" value="fixed" {{ $pricingMode === 'fixed' ? 'checked' : '' }}>
                            <div class="mode-card">
                                <div class="mode-icon">💰</div>
                                <div class="mode-title">Fixed Rate</div>
                                <div class="mode-desc">Fixed price per session</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Price Per Minute (₱)</label>
                    <div class="price-input-wrapper">
                        <span class="currency-symbol">₱</span>
                        <input type="number" name="price_per_minute" class="form-input price-input" value="{{ $pricePerMinute }}" step="0.01" min="0" required>
                        <span class="price-suffix">/ minute</span>
                    </div>
                    <small class="form-hint">This rate is displayed on the lockscreen and used for billing calculations</small>
                </div>

                <div class="calculation-preview">
                    <div class="preview-title">💵 Rate Preview</div>
                    <div class="preview-grid">
                        <div class="preview-item">
                            <div class="preview-label">1 minute</div>
                            <div class="preview-value">₱{{ number_format($pricePerMinute, 2) }}</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">15 minutes</div>
                            <div class="preview-value">₱{{ number_format($pricePerMinute * 15, 2) }}</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">30 minutes</div>
                            <div class="preview-value">₱{{ number_format($pricePerMinute * 30, 2) }}</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">1 hour</div>
                            <div class="preview-value">₱{{ number_format($pricePerMinute * 60, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="form-actions">
            <button type="submit" class="btn-save">
                💾 Save Rates
            </button>
            <a href="{{ route('dashboard') }}" class="btn-cancel">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
.rates-container {
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

.rates-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.rate-card {
    padding: 24px;
    background: var(--bg-deep);
    border: 2px solid var(--border);
    border-radius: 12px;
    text-align: center;
    transition: all .3s ease;
}

.rate-card:hover {
    border-color: var(--neon);
    box-shadow: 0 0 20px rgba(0,200,255,0.2);
}

.coin-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.coin-label {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 16px;
}

.rate-input-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 12px;
}

.rate-input {
    width: 80px;
    padding: 10px;
    background: var(--bg-mid);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text-hi);
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    text-align: center;
    outline: none;
}

.rate-input:focus {
    border-color: var(--neon);
    box-shadow: 0 0 12px rgba(0,200,255,0.2);
}

.rate-unit {
    font-size: 13px;
    color: var(--text-mid);
    font-family: 'DM Mono', monospace;
}

.rate-hint {
    font-size: 11px;
    color: var(--text-lo);
    font-family: 'DM Mono', monospace;
}

.rate-hint span {
    color: var(--neon);
    font-weight: 700;
}

.info-box {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: rgba(0,200,255,0.08);
    border: 1px solid rgba(0,200,255,0.2);
    border-radius: 10px;
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

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-mid);
    margin-bottom: 12px;
    font-family: 'DM Mono', monospace;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pricing-mode-selector {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
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
    text-align: center;
}

.mode-option input[type="radio"]:checked + .mode-card {
    border-color: var(--neon);
    background: rgba(0,200,255,0.08);
    box-shadow: 0 0 20px rgba(0,200,255,0.2);
}

.mode-icon {
    font-size: 40px;
    margin-bottom: 12px;
}

.mode-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 8px;
}

.mode-desc {
    font-size: 12px;
    color: var(--text-mid);
}

.price-input-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-deep);
    border: 1px solid var(--border);
    border-radius: 10px;
}

.currency-symbol {
    font-size: 18px;
    font-weight: 700;
    color: var(--neon);
}

.price-input {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-hi);
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    outline: none;
}

.price-suffix {
    font-size: 13px;
    color: var(--text-mid);
    font-family: 'DM Mono', monospace;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-deep);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-hi);
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    outline: none;
}

.form-hint {
    font-size: 11px;
    color: var(--text-lo);
    margin-top: 8px;
    display: block;
    font-family: 'DM Mono', monospace;
}

.calculation-preview {
    padding: 24px;
    background: var(--bg-deep);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-top: 24px;
}

.preview-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-hi);
    margin-bottom: 16px;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.preview-item {
    text-align: center;
    padding: 16px;
    background: var(--bg-mid);
    border-radius: 8px;
}

.preview-label {
    font-size: 11px;
    color: var(--text-mid);
    margin-bottom: 8px;
    font-family: 'DM Mono', monospace;
}

.preview-value {
    font-size: 20px;
    font-weight: 800;
    color: var(--neon);
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

@media (max-width: 1024px) {
    .rates-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .preview-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .rates-grid,
    .pricing-mode-selector,
    .preview-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Live update rate hints
document.querySelectorAll('.rate-input').forEach(input => {
    input.addEventListener('input', function() {
        const coinType = this.name.replace('coin_', '').replace('_rate', '');
        const displayId = 'coin' + coinType + 'Display';
        const displayElement = document.getElementById(displayId);
        if (displayElement) {
            displayElement.textContent = this.value;
        }
    });
});
</script>
@endsection
