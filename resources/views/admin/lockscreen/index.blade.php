@extends('layouts.app')
@section('title', 'Lock Screen Settings')
@section('content')
<div class="card">
    <h3>Lock Screen Configuration</h3>
    <form method="POST" action="{{ route('lock.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <label>Title</label>
        <input type="text" name="lockscreen_title" value="{{ $lockscreenTitle }}">
        <label>Message</label>
        <input type="text" name="lockscreen_message" value="{{ $lockscreenMessage }}">
        <label>Theme</label>
        <select name="lockscreen_theme">
            <option value="dark" {{ $lockscreenTheme === 'dark' ? 'selected' : '' }}>Dark</option>
            <option value="light" {{ $lockscreenTheme === 'light' ? 'selected' : '' }}>Light</option>
            <option value="blue" {{ $lockscreenTheme === 'blue' ? 'selected' : '' }}>Blue</option>
            <option value="green" {{ $lockscreenTheme === 'green' ? 'selected' : '' }}>Green</option>
        </select>
        <label>Show Timer</label>
        <select name="lockscreen_show_timer">
            <option value="true" {{ $showTimer === 'true' ? 'selected' : '' }}>Yes</option>
            <option value="false" {{ $showTimer === 'false' ? 'selected' : '' }}>No</option>
        </select>
        <label>Show Price</label>
        <select name="lockscreen_show_price">
            <option value="true" {{ $showPrice === 'true' ? 'selected' : '' }}>Yes</option>
            <option value="false" {{ $showPrice === 'false' ? 'selected' : '' }}>No</option>
        </select>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
