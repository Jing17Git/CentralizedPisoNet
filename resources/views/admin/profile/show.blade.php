@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="card">
    <h3>Admin Profile</h3>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <label>Name</label>
        <input type="text" name="name" value="{{ $user->name }}" required>
        @error('name') <div style="color:#e74c3c;font-size:12px;margin-top:-8px;margin-bottom:8px;">{{ $message }}</div> @enderror
        <label>Username</label>
        <input type="text" name="username" value="{{ $user->username }}">
        <label>Email</label>
        <input type="email" name="email" value="{{ $user->email }}" required>
        @error('email') <div style="color:#e74c3c;font-size:12px;margin-top:-8px;margin-bottom:8px;">{{ $message }}</div> @enderror
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
