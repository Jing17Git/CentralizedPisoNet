<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - CentralizedPisoNet Management System</title>
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
            overflow-x: hidden;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 10%,  rgba(0,120,255,0.14) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 80%,  rgba(0,200,255,0.10) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 50%,  rgba(0,50,120,0.18) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(0,200,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,200,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none; z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .header {
            position: absolute;
            top: 0;
            right: 0;
            padding: 30px 40px;
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .3s ease;
            display: inline-block;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-mid);
        }

        .btn-outline:hover {
            border-color: var(--neon);
            color: var(--neon);
            box-shadow: 0 0 20px rgba(0,200,255,0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--neon2), var(--neon));
            border: none;
            color: var(--bg-deep);
            box-shadow: var(--neon-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0,200,255,0.5);
        }

        .hero {
            text-align: center;
            max-width: 900px;
            animation: fadeUp .8s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--neon2), var(--neon));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin: 0 auto 30px;
            box-shadow: var(--neon-glow);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .title {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            background: linear-gradient(135deg, var(--text-hi), var(--neon));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 20px;
            color: var(--text-mid);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 60px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-top: 80px;
            max-width: 1000px;
            width: 100%;
        }

        .feature-card {
            background: rgba(6, 22, 44, 0.72);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 30px;
            backdrop-filter: blur(16px);
            transition: all .3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--neon);
            box-shadow: 0 10px 40px rgba(0,200,255,0.2);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 16px;
        }

        .feature-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-hi);
        }

        .feature-desc {
            font-size: 14px;
            color: var(--text-mid);
            line-height: 1.6;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: var(--text-lo);
            font-family: 'DM Mono', monospace;
        }

        @media (max-width: 768px) {
            .title { font-size: 36px; }
            .subtitle { font-size: 16px; }
            .cta-buttons { flex-direction: column; }
            .header { position: relative; padding: 20px; justify-content: center; }
            .features { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">

        @if (Route::has('login'))
        <div class="header">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                @endif
            @endauth
        </div>
        @endif

        <div class="hero">
            <div class="logo">🖥</div>
            <h1 class="title">CentralizedPisoNet</h1>
            <p class="subtitle">
                Modern, Powerful, and Efficient PisoNet Management System.<br>
                Manage your internet cafe with real-time monitoring, session control, and comprehensive analytics.
            </p>
            <div class="cta-buttons">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
                    <a href="#features" class="btn btn-outline">Learn More</a>
                @endauth
            </div>
        </div>

        <div class="features" id="features">
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Real-time Dashboard</div>
                <div class="feature-desc">
                    Monitor all PC units, active sessions, and earnings in real-time with live updates.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🖥️</div>
                <div class="feature-title">PC Unit Management</div>
                <div class="feature-desc">
                    Easily manage and monitor all your PC units with status tracking and control.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <div class="feature-title">Session Monitoring</div>
                <div class="feature-desc">
                    Track user sessions, extend time, and manage active users efficiently.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <div class="feature-title">Financial Tracking</div>
                <div class="feature-desc">
                    Complete transaction history, earnings reports, and coin insert tracking.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <div class="feature-title">Analytics & Reports</div>
                <div class="feature-desc">
                    Generate detailed reports and export data in PDF or Excel format.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <div class="feature-title">Secure & Reliable</div>
                <div class="feature-desc">
                    Built with Laravel framework ensuring security and reliability.
                </div>
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} CentralizedPisoNet Management System. All rights reserved.
        </div>

    </div>
</body>
</html>