<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - PisoNet Central</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg-deep:     #040c18;
    --bg-mid:      #071425;
    --neon:        #00c8ff;
    --neon2:       #0077ff;
    --neon-glow:   0 0 18px #00c8ff88, 0 0 40px #0077ff44;
    --accent-teal: #00ffcc;
    --text-hi:     #e8f4ff;
    --text-mid:    #7fa8c9;
    --text-lo:     #3a5570;
    --border:      rgba(0,200,255,0.12);
    --glass:       rgba(0,180,255,0.06);
    --radius:      14px;
  }

  html, body { height: 100%; }
  body {
    font-family: 'Syne', sans-serif;
    background: var(--bg-deep);
    color: var(--text-hi);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    overflow-x: hidden;
  }

  /* Background mesh */
  body::before {
    content: '';
    position: fixed; inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 15% 10%,  rgba(0,120,255,0.14) 0%, transparent 60%),
      radial-gradient(ellipse 60% 50% at 85% 80%,  rgba(0,200,255,0.10) 0%, transparent 60%),
      radial-gradient(ellipse 40% 40% at 50% 50%,  rgba(0,50,120,0.18) 0%, transparent 70%);
    pointer-events: none; z-index: 0;
  }

  /* Grid scan-line overlay */
  body::after {
    content: '';
    position: fixed; inset: 0;
    background-image:
      linear-gradient(rgba(0,200,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,200,255,0.025) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none; z-index: 0;
  }

  .login-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    padding: 20px;
  }

  .login-card {
    background: rgba(6, 22, 44, 0.72);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    backdrop-filter: blur(18px);
    padding: 40px 36px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  }

  .login-header {
    text-align: center;
    margin-bottom: 36px;
  }

  .brand-icon {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, var(--neon2), var(--neon));
    border-radius: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 28px;
    box-shadow: var(--neon-glow);
    margin-bottom: 16px;
  }

  .brand-name {
    font-size: 24px; font-weight: 800; color: var(--text-hi);
    letter-spacing: 0.5px; margin-bottom: 4px;
  }

  .brand-sub {
    font-size: 12px; color: var(--neon);
    font-family: 'DM Mono', monospace;
    letter-spacing: 2px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-label {
    display: block;
    font-size: 11px;
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

  .btn-login {
    width: 100%;
    padding: 14px;
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
    margin-top: 10px;
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 24px rgba(0,200,255,0.5);
  }

  .alert {
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 13px;
  }

  .alert-success {
    background: rgba(0,230,118,0.15);
    border: 1px solid rgba(0,230,118,0.3);
    color: #00e676;
  }

  .alert-error {
    background: rgba(255,59,107,0.15);
    border: 1px solid rgba(255,59,107,0.3);
    color: #ff3b6b;
  }

  .error-message {
    color: #ff3b6b;
    font-size: 12px;
    margin-top: 6px;
    font-family: 'DM Mono', monospace;
  }

  .demo-accounts {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
  }

  .demo-title {
    font-size: 10px;
    color: var(--text-lo);
    font-family: 'DM Mono', monospace;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    text-align: center;
    margin-bottom: 14px;
  }

  .demo-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: var(--glass);
    border-radius: 8px;
    margin-bottom: 8px;
    font-size: 12px;
  }

  .demo-role {
    color: var(--neon);
    font-weight: 600;
  }

  .demo-email {
    color: var(--text-mid);
    font-family: 'DM Mono', monospace;
    font-size: 11px;
  }

  .demo-pass {
    color: var(--text-mid);
    font-family: 'DM Mono', monospace;
    font-size: 11px;
  }
</style>
</head>
<body>

<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <div class="brand-icon">🖥</div>
      <div class="brand-name">PisoNet</div>
      <div class="brand-sub">CENTRAL LOGIN</div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label class="form-label" for="email">Email Address</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          class="form-input" 
          placeholder="Enter your email"
          value="{{ old('email') }}"
          required
        >
        @error('email')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="form-input" 
          placeholder="Enter your password"
          required
        >
        @error('password')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn-login">SIGN IN</button>
    </form>

    <div class="login-link" style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border); font-size: 13px; color: var(--text-mid);">
      Don't have an account? <a href="{{ route('register') }}" style="color: var(--neon); text-decoration: none; font-weight: 600;">Register</a>
    </div>

  </div>
</div>

</body>
</html>

