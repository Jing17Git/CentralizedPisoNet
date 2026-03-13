@extends('layouts.admin')

@section('title', 'Edit Profile')

@section('content')
<div class="profile-container">
    <!-- Profile Card -->
    <div class="glass-card profile-header-card">
        <div class="profile-header">
            <div class="profile-avatar-large">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture" id="currentAvatar">
                @else
                    <div class="avatar-placeholder" id="currentAvatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="profile-info">
                <h2 class="profile-name">{{ auth()->user()->name }}</h2>
                <p class="profile-role">{{ strtoupper(auth()->user()->role ?? 'ADMIN') }}</p>
                <p class="profile-email">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="glass-card" style="margin-top: 20px;">
        <div class="card-header">
            <div>
                <div class="card-title">Edit <span>Profile</span></div>
                <div class="card-sub">Update your personal information</div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" style="padding: 20px 0;">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="form-group">
                <label class="form-label">Profile Picture</label>
                <div class="profile-picture-upload">
                    <div class="preview-container">
                        <div class="preview-avatar" id="previewAvatar">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Preview">
                            @else
                                <div class="avatar-placeholder-small">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="upload-controls">
                        <input type="file" name="profile_picture" id="profilePicture" accept="image/*" style="display: none;">
                        <button type="button" class="btn-upload" onclick="document.getElementById('profilePicture').click()">
                            📷 Choose Image
                        </button>
                        <span class="upload-hint">JPG, PNG, GIF (Max 2MB)</span>
                    </div>
                </div>
                @error('profile_picture')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Full Name -->
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    class="form-input" 
                    value="{{ old('name', auth()->user()->name) }}"
                    required
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username -->
            <div class="form-group">
                <label class="form-label">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    class="form-input" 
                    value="{{ old('username', auth()->user()->username) }}"
                    placeholder="Enter username"
                >
                @error('username')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-input" 
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input 
                    type="text" 
                    name="phone" 
                    class="form-input" 
                    value="{{ old('phone', auth()->user()->phone) }}"
                    placeholder="Enter phone number"
                >
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address -->
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea 
                    name="address" 
                    class="form-input" 
                    rows="3"
                    placeholder="Enter your address"
                >{{ old('address', auth()->user()->address) }}</textarea>
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Section -->
            <div class="form-section-divider">
                <span>Change Password (Optional)</span>
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Leave blank to keep current password"
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="form-input" 
                    placeholder="Confirm new password"
                >
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn-update">
                    💾 Update Profile
                </button>
                <a href="{{ route('admin.profile.show') }}" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.profile-container {
    max-width: 1000px;
    margin: 0 auto;
}

.profile-header-card {
    padding: 40px;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 30px;
}

.profile-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--neon);
    box-shadow: var(--neon-glow);
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    color: var(--bg-deep);
    font-size: 48px;
    font-weight: 800;
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-hi);
    margin: 0 0 8px 0;
}

.profile-role {
    font-size: 12px;
    color: var(--neon);
    font-family: 'DM Mono', monospace;
    letter-spacing: 2px;
    margin: 0 0 8px 0;
}

.profile-email {
    font-size: 14px;
    color: var(--text-mid);
    margin: 0;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 12px;
    color: var(--text-mid);
    font-family: 'DM Mono', monospace;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.form-input {
    width: 100%;
    padding: 14px 16px;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-hi);
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    outline: none;
    transition: all .2s ease;
}

.form-input:focus {
    border-color: var(--neon);
    box-shadow: 0 0 12px rgba(0,200,255,0.2);
}

.form-input::placeholder {
    color: var(--text-lo);
}

textarea.form-input {
    resize: vertical;
    min-height: 80px;
}

.profile-picture-upload {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 10px;
}

.preview-container {
    flex-shrink: 0;
}

.preview-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--neon);
}

.preview-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-small {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    color: var(--bg-deep);
    font-size: 32px;
    font-weight: 800;
}

.upload-controls {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-upload {
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border: none;
    border-radius: 8px;
    color: var(--bg-deep);
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 12px rgba(0,200,255,0.4);
}

.upload-hint {
    font-size: 11px;
    color: var(--text-lo);
    font-family: 'DM Mono', monospace;
}

.form-section-divider {
    margin: 32px 0 24px 0;
    padding: 12px 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.form-section-divider span {
    font-size: 13px;
    color: var(--text-mid);
    font-weight: 600;
    letter-spacing: 0.5px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.btn-update {
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

.btn-update:hover {
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

.error-message {
    color: #ff3b6b;
    font-size: 12px;
    margin-top: 6px;
    font-family: 'DM Mono', monospace;
}
</style>

<script>
// Image preview
document.getElementById('profilePicture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewAvatar = document.getElementById('previewAvatar');
            previewAvatar.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
            
            const currentAvatar = document.getElementById('currentAvatar');
            if (currentAvatar.tagName === 'IMG') {
                currentAvatar.src = e.target.result;
            } else {
                currentAvatar.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">';
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
