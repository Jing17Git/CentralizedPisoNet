@extends('layouts.app')
@section('title', 'Profile')
@section('content')

<div class="card" style="max-width:520px;">
    <div class="card-header">
        <h3>◎ Admin Profile</h3>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label for="profile_name">Full Name</label>
        <input type="text" id="profile_name" name="name" value="{{ $user->name }}" required autocomplete="name">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror

        <label for="profile_username">Username</label>
        <input type="text" id="profile_username" name="username" value="{{ $user->username }}" autocomplete="username">

        <label for="profile_email">Email Address</label>
        <input type="email" id="profile_email" name="email" value="{{ $user->email }}" required autocomplete="email">
        @error('email') <span class="field-error">{{ $message }}</span> @enderror

        <div style="border-top:1px solid var(--border);margin:4px 0 16px;"></div>

        <label for="profile_password">New Password <span style="color:var(--text-muted);font-weight:400;text-transform:none;">(leave blank to keep current)</span></label>
        <input type="password" id="profile_password" name="password" autocomplete="new-password">
        @error('password') <span class="field-error">{{ $message }}</span> @enderror

        <label for="profile_password_confirm">Confirm Password</label>
        <input type="password" id="profile_password_confirm" name="password_confirmation" autocomplete="new-password">

        <div style="border-top:1px solid var(--border);margin:4px 0 16px;"></div>
        <button type="submit" class="btn btn-primary" id="update-profile-btn">Update Profile</button>
    </form>
</div>
@endsection
