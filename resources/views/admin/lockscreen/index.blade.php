@extends('layouts.app')
@section('title', 'Lock Screen Settings')
@section('content')

<div class="card" style="max-width:520px;">
    <div class="card-header">
        <h3>◉ Lock Screen Configuration</h3>
    </div>
    <form method="POST" action="{{ route('lock.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label for="lockscreen_title">Title</label>
        <input type="text" id="lockscreen_title" name="lockscreen_title" value="{{ $lockscreenTitle }}">

        <label for="lockscreen_message">Message</label>
        <input type="text" id="lockscreen_message" name="lockscreen_message" value="{{ $lockscreenMessage }}">

        <label for="lockscreen_theme">Theme</label>
        <select id="lockscreen_theme" name="lockscreen_theme">
            <option value="dark"  {{ $lockscreenTheme === 'dark'  ? 'selected' : '' }}>Dark</option>
            <option value="light" {{ $lockscreenTheme === 'light' ? 'selected' : '' }}>Light</option>
            <option value="blue"  {{ $lockscreenTheme === 'blue'  ? 'selected' : '' }}>Blue</option>
            <option value="green" {{ $lockscreenTheme === 'green' ? 'selected' : '' }}>Green</option>
        </select>

        <div class="form-row">
            <div>
                <label for="lockscreen_show_timer">Show Timer</label>
                <select id="lockscreen_show_timer" name="lockscreen_show_timer">
                    <option value="true"  {{ $showTimer === 'true'  ? 'selected' : '' }}>Yes</option>
                    <option value="false" {{ $showTimer === 'false' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div>
                <label for="lockscreen_show_price">Show Price</label>
                <select id="lockscreen_show_price" name="lockscreen_show_price">
                    <option value="true"  {{ $showPrice === 'true'  ? 'selected' : '' }}>Yes</option>
                    <option value="false" {{ $showPrice === 'false' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <div style="border-top:1px solid var(--border);margin:4px 0 16px;"></div>
        <button type="submit" class="btn btn-primary" id="save-lock-btn">Save Configuration</button>
    </form>
</div>
@endsection
