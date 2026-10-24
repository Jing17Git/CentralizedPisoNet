<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — PisoNet Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep:  #0D1117;
            --bg-card:  #161B22;
            --border:   #30363D;
            --accent:   #58A6FF;
            --text:     #E6EDF3;
            --text-muted: #8B949E;
            --danger:   #F85149;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-deep);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .auth-wrap { width: 100%; max-width: 380px; padding: 20px; }
        .auth-brand { text-align: center; margin-bottom: 28px; }
        .auth-brand .brand-icon {
            width: 48px; height: 48px;
            background: var(--accent); border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin: 0 auto 12px;
        }
        .auth-brand h1 { font-size: 20px; font-weight: 700; color: var(--text); }
        .auth-brand p { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
        .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 28px; }
        label {
            display: block; font-size: 11px; font-weight: 600;
            color: var(--text-muted); text-transform: uppercase;
            letter-spacing: .5px; margin-bottom: 6px;
        }
        input {
            width: 100%; background: var(--bg-deep); color: var(--text);
            border: 1px solid var(--border); padding: 9px 12px; border-radius: 6px;
            margin-bottom: 16px; font-size: 14px; font-family: inherit;
            outline: none; transition: border-color .15s;
        }
        input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(88,166,255,.1); }
        .field-error { display: block; color: var(--danger); font-size: 11px; margin-top: -12px; margin-bottom: 12px; }
        button[type="submit"] {
            width: 100%; background: var(--accent); color: #0D1117;
            border: none; padding: 11px; border-radius: 6px;
            font-size: 14px; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: opacity .15s; margin-top: 4px;
        }
        button[type="submit"]:hover { opacity: .88; }
        .auth-footer { text-align: center; margin-top: 18px; font-size: 12px; color: var(--text-muted); }
        .auth-footer a { color: var(--accent); text-decoration: none; font-weight: 500; }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-brand">
        <div class="brand-icon">⚡</div>
        <h1>Create Account</h1>
        <p>Register a new admin account</p>
    </div>

    <div class="auth-card">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label for="reg-name">Full Name</label>
            <input type="text" id="reg-name" name="name" value="{{ old('name') }}" required autocomplete="name">
            @error('name') <span class="field-error">{{ $message }}</span> @enderror

            <label for="reg-email">Email Address</label>
            <input type="email" id="reg-email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email') <span class="field-error">{{ $message }}</span> @enderror

            <label for="reg-password">Password</label>
            <input type="password" id="reg-password" name="password" required autocomplete="new-password">
            @error('password') <span class="field-error">{{ $message }}</span> @enderror

            <label for="reg-password-confirm">Confirm Password</label>
            <input type="password" id="reg-password-confirm" name="password_confirmation" required autocomplete="new-password">

            <button type="submit" id="register-submit-btn">Create Account</button>
        </form>
    </div>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>
</body>
</html>
