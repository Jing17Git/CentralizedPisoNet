@extends('layouts.admin')

@section('title', 'Lockscreen Settings')

@section('content')
<div class="lockscreen-container">
    <div class="page-header">
        <div>
            <div class="page-title">Lockscreen <span>Settings</span></div>
            <div class="page-subtitle">Customize the lockscreen displayed on client computers</div>
        </div>
        <a href="{{ route('lockscreen.preview') }}" class="btn-preview" target="_blank">
            👁️ Preview Lockscreen
        </a>
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

    <form method="POST" action="{{ route('lockscreen.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Settings -->
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="card-title">Basic <span>Settings</span></div>
                    <div class="card-sub">Configure title, message, and branding</div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Lockscreen Title <span class="required">*</span></label>
                    <input type="text" name="lockscreen_title" class="form-input" value="{{ $lockscreenTitle }}" required>
                    <small class="form-hint">Main title displayed on lockscreen</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Lockscreen Message <span class="required">*</span></label>
                    <input type="text" name="lockscreen_message" class="form-input" value="{{ $lockscreenMessage }}" required>
                    <small class="form-hint">Instruction message for users</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Logo</label>
                        <div class="image-upload-box">
                            @if($lockscreenLogo)
                                <img src="{{ asset('storage/' . $lockscreenLogo) }}" alt="Logo" class="preview-image" id="logoPreview">
                            @else
                                <div class="upload-placeholder" id="logoPreview">
                                    <span>📷</span>
                                    <span>No logo uploaded</span>
                                </div>
                            @endif
                            <input type="file" name="lockscreen_logo" id="logoInput" accept="image/*" style="display: none;" onchange="previewImage(this, 'logoPreview')">
                            <button type="button" class="btn-upload-small" onclick="document.getElementById('logoInput').click()">
                                Choose Logo
                            </button>
                        </div>
                        <small class="form-hint">PNG, JPG, SVG (Max 2MB)</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Image</label>
                        <div class="image-upload-box">
                            @if($lockscreenBackground)
                                <img src="{{ asset('storage/' . $lockscreenBackground) }}" alt="Background" class="preview-image" id="backgroundPreview">
                            @else
                                <div class="upload-placeholder" id="backgroundPreview">
                                    <span>🖼️</span>
                                    <span>No background uploaded</span>
                                </div>
                            @endif
                            <input type="file" name="lockscreen_background" id="backgroundInput" accept="image/*" style="display: none;" onchange="previewImage(this, 'backgroundPreview')">
                            <button type="button" class="btn-upload-small" onclick="document.getElementById('backgroundInput').click()">
                                Choose Background
                            </button>
                        </div>
                        <small class="form-hint">JPG, PNG (Max 5MB)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appearance Settings -->
        <div class="glass-card" style="margin-top: 20px;">
            <div class="card-header">
                <div>
                    <div class="card-title">Appearance <span>Settings</span></div>
                    <div class="card-sub">Choose theme and display options</div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Theme</label>
                    <div class="theme-selector">
                        <label class="theme-option">
                            <input type="radio" name="lockscreen_theme" value="dark" {{ $lockscreenTheme === 'dark' ? 'checked' : '' }}>
                            <div class="theme-card dark">
                                <div class="theme-preview"></div>
                                <div class="theme-name">Dark</div>
                            </div>
                        </label>

                        <label class="theme-option">
                            <input type="radio" name="lockscreen_theme" value="light" {{ $lockscreenTheme === 'light' ? 'checked' : '' }}>
                            <div class="theme-card light">
                                <div class="theme-preview"></div>
                                <div class="theme-name">Light</div>
                            </div>
                        </label>

                        <label class="theme-option">
                            <input type="radio" name="lockscreen_theme" value="blue" {{ $lockscreenTheme === 'blue' ? 'checked' : '' }}>
                            <div class="theme-card blue">
                                <div class="theme-preview"></div>
                                <div class="theme-name">Blue</div>
                            </div>
                        </label>

                        <label class="theme-option">
                            <input type="radio" name="lockscreen_theme" value="green" {{ $lockscreenTheme === 'green' ? 'checked' : '' }}>
                            <div class="theme-card green">
                                <div class="theme-preview"></div>
                                <div class="theme-name">Green</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Show Timer</label>
                        <div class="toggle-group">
                            <label class="toggle-option">
                                <input type="radio" name="lockscreen_show_timer" value="true" {{ $showTimer === 'true' ? 'checked' : '' }}>
                                <span class="toggle-btn">Yes</span>
                            </label>
                            <label class="toggle-option">
                                <input type="radio" name="lockscreen_show_timer" value="false" {{ $showTimer === 'false' ? 'checked' : '' }}>
                                <span class="toggle-btn">No</span>
                            </label>
                        </div>
                        <small class="form-hint">Display remaining time on lockscreen</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Show Price</label>
                        <div class="toggle-group">
                            <label class="toggle-option">
                                <input type="radio" name="lockscreen_show_price" value="true" {{ $showPrice === 'true' ? 'checked' : '' }}>
                                <span class="toggle-btn">Yes</span>
                            </label>
                            <label class="toggle-option">
                                <input type="radio" name="lockscreen_show_price" value="false" {{ $showPrice === 'false' ? 'checked' : '' }}>
                                <span class="toggle-btn">No</span>
                            </label>
                        </div>
                        <small class="form-hint">Display price per minute</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="form-actions">
            <button type="submit" class="btn-save">
                💾 Save Lockscreen Settings
            </button>
            <a href="{{ route('dashboard') }}" class="btn-cancel">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
.lockscreen-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
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

.btn-preview {
    padding: 12px 24px;
    background: linear-gradient(135deg, #9c27b0, #673ab7);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(156,39,176,0.3);
    transition: all .2s ease;
}

.btn-preview:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(156,39,176,0.4);
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

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-mid);
    margin-bottom: 8px;
    font-family: 'DM Mono', monospace;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.required {
    color: var(--accent-red);
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
    transition: border-color .2s;
}

.form-input:focus {
    border-color: var(--neon);
    box-shadow: 0 0 12px rgba(0,200,255,0.2);
}

.form-hint {
    font-size: 11px;
    color: var(--text-lo);
    margin-top: 6px;
    display: block;
    font-family: 'DM Mono', monospace;
}

.image-upload-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 20px;
    background: var(--bg-deep);
    border: 2px dashed var(--border);
    border-radius: 10px;
}

.preview-image {
    width: 100%;
    max-width: 200px;
    height: 120px;
    object-fit: contain;
    border-radius: 8px;
}

.upload-placeholder {
    width: 100%;
    height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--text-lo);
    font-size: 12px;
}

.upload-placeholder span:first-child {
    font-size: 32px;
}

.btn-upload-small {
    padding: 8px 16px;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border: none;
    border-radius: 8px;
    color: var(--bg-deep);
    font-family: 'Syne', sans-serif;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.btn-upload-small:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,200,255,0.4);
}

.theme-selector {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.theme-option {
    cursor: pointer;
}

.theme-option input[type="radio"] {
    display: none;
}

.theme-card {
    padding: 16px;
    background: var(--bg-deep);
    border: 2px solid var(--border);
    border-radius: 10px;
    transition: all .3s ease;
    text-align: center;
}

.theme-option input[type="radio"]:checked + .theme-card {
    border-color: var(--neon);
    box-shadow: 0 0 20px rgba(0,200,255,0.2);
}

.theme-preview {
    width: 100%;
    height: 60px;
    border-radius: 6px;
    margin-bottom: 12px;
}

.theme-card.dark .theme-preview {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
}

.theme-card.light .theme-preview {
    background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
}

.theme-card.blue .theme-preview {
    background: linear-gradient(135deg, #0d47a1, #1976d2);
}

.theme-card.green .theme-preview {
    background: linear-gradient(135deg, #1b5e20, #388e3c);
}

.theme-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-hi);
}

.toggle-group {
    display: flex;
    gap: 12px;
}

.toggle-option {
    cursor: pointer;
}

.toggle-option input[type="radio"] {
    display: none;
}

.toggle-btn {
    display: inline-block;
    padding: 10px 24px;
    background: var(--bg-deep);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text-mid);
    font-size: 13px;
    font-weight: 600;
    transition: all .2s ease;
}

.toggle-option input[type="radio"]:checked + .toggle-btn {
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border-color: var(--neon);
    color: var(--bg-deep);
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

@media (max-width: 768px) {
    .form-row,
    .theme-selector {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
}
</style>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="preview-image">';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
