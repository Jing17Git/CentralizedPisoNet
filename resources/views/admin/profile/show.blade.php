@extends('layouts.admin')

@section('title', 'Administrator Profile')

@section('content')
<div class="profile-container">
    <!-- Profile Card -->
    <div class="glass-card profile-header-card">
        <div class="profile-header">
            <div class="profile-avatar-large">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture">
                @else
                    <div class="avatar-placeholder">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="profile-info">
                <h2 class="profile-name">{{ auth()->user()->name }}</h2>
                <p class="profile-role">{{ strtoupper(auth()->user()->role ?? 'ADMIN') }}</p>
                <p class="profile-email">{{ auth()->user()->email }}</p>
            </div>
            <div class="profile-actions">
                <a href="{{ route('admin.profile.edit') }}" class="btn-edit-profile">
                    <span>✏️</span> Edit Profile
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success" style="margin-top: 20px;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Profile Details -->
    <div class="glass-card" style="margin-top: 20px;">
        <div class="card-header">
            <div>
                <div class="card-title">Profile <span>Information</span></div>
                <div class="card-sub">Your personal details</div>
            </div>
        </div>
        
        <div class="profile-details">
            <div class="detail-row">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ auth()->user()->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Username</div>
                <div class="detail-value">{{ auth()->user()->username ?? 'Not set' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email Address</div>
                <div class="detail-value">{{ auth()->user()->email }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Phone Number</div>
                <div class="detail-value">{{ auth()->user()->phone ?? 'Not set' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Address</div>
                <div class="detail-value">{{ auth()->user()->address ?? 'Not set' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Role</div>
                <div class="detail-value">
                    <span class="role-badge">{{ strtoupper(auth()->user()->role ?? 'ADMIN') }}</span>
                </div>
            </div>
        </div>
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

.btn-edit-profile {
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border: none;
    border-radius: 10px;
    color: var(--bg-deep);
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--neon-glow);
    transition: all .2s ease;
}

.btn-edit-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 24px rgba(0,200,255,0.5);
}

.profile-details {
    padding: 20px 0;
}

.detail-row {
    display: flex;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    width: 200px;
    font-size: 12px;
    color: var(--text-mid);
    font-family: 'DM Mono', monospace;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.detail-value {
    flex: 1;
    font-size: 14px;
    color: var(--text-hi);
    font-weight: 500;
}

.role-badge {
    padding: 6px 12px;
    background: rgba(0,200,255,0.15);
    border: 1px solid var(--neon);
    border-radius: 6px;
    color: var(--neon);
    font-size: 11px;
    font-family: 'DM Mono', monospace;
    letter-spacing: 1px;
}

.alert-success {
    padding: 16px 20px;
    background: rgba(0,230,118,0.15);
    border: 1px solid rgba(0,230,118,0.3);
    border-radius: 10px;
    color: #00e676;
    font-size: 14px;
}
</style>
@endsection
