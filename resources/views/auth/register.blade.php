<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CentralizedPisoNet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a2e; color: #eee; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .box { background: #16213e; padding: 40px; border-radius: 10px; width: 360px; }
        h2 { color: #00c8ff; text-align: center; margin-bottom: 24px; }
        label { display: block; color: #aaa; margin-bottom: 4px; font-size: 13px; }
        input { width: 100%; background: #0f3460; color: #eee; border: 1px solid #1a4a7a; padding: 10px 12px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
        button { width: 100%; background: #00c8ff; color: #000; border: none; padding: 12px; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; }
        .error { color: #e74c3c; font-size: 12px; margin-top: -12px; margin-bottom: 12px; }
        .login-link { text-align: center; margin-top: 16px; font-size: 13px; color: #aaa; }
        .login-link a { color: #00c8ff; text-decoration: none; }
    </style>
</head>
<body>
<div class="box">
    <h2>⚡ Create Account</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email') <div class="error">{{ $message }}</div> @enderror
        <label>Password</label>
        <input type="password" name="password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>
        <button type="submit">Register</button>
    </form>
    <div class="login-link">Already have an account? <a href="{{ route('login') }}">Login</a></div>
</div>
</body>
</html>
