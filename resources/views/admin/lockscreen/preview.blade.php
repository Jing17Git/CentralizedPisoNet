<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lockscreen Preview - {{ $lockscreenTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Syne', sans-serif;
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Theme Colors */
        body.dark {
            background: linear-gradient(135deg, #0a0e27 0%, #16213e 100%);
            color: #ffffff;
        }

        body.light {
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            color: #1a1a1a;
        }

        body.blue {
            background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
            color: #ffffff;
        }

        body.green {
            background: linear-gradient(135deg, #1b5e20 0%, #388e3c 100%);
            color: #ffffff;
        }

        .lockscreen-bg {
            position: fixed;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0.3;
            z-index: 0;
        }

        .lockscreen-container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }

        .lockscreen-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            object-fit: contain;
        }

        .lockscreen-title {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .lockscreen-message {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .lockscreen-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
        }

        .info-box {
            padding: 20px 30px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        body.light .info-box {
            background: rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.1);
        }

        .info-label {
            font-size: 12px;
            opacity: 0.7;
            margin-bottom: 8px;
            font-family: 'DM Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            font-size: 32px;
            font-weight: 800;
        }

        .coin-animation {
            width: 80px;
            height: 80px;
            margin: 40px auto;
            font-size: 60px;
            animation: coinBounce 2s ease-in-out infinite;
        }

        @keyframes coinBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .status-text {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 20px;
            font-family: 'DM Mono', monospace;
        }
    </style>
</head>
<body class="{{ $lockscreenTheme }}">
    @if($lockscreenBackground)
    <div class="lockscreen-bg" style="background-image: url('{{ asset('storage/' . $lockscreenBackground) }}');"></div>
    @endif

    <div class="lockscreen-container">
        @if($lockscreenLogo)
        <img src="{{ asset('storage/' . $lockscreenLogo) }}" alt="Logo" class="lockscreen-logo">
        @endif

        <h1 class="lockscreen-title">{{ $lockscreenTitle }}</h1>
        <p class="lockscreen-message">{{ $lockscreenMessage }}</p>

        <div class="coin-animation">🪙</div>

        <div class="lockscreen-info">
            @if($showTimer === 'true')
            <div class="info-box">
                <div class="info-label">Time Remaining</div>
                <div class="info-value" id="timer">00:00</div>
            </div>
            @endif

            @if($showPrice === 'true')
            <div class="info-box">
                <div class="info-label">Price Per Minute</div>
                <div class="info-value">₱{{ number_format($pricePerMinute, 2) }}</div>
            </div>
            @endif
        </div>

        <p class="status-text">LOCKED • Insert coin to continue</p>
    </div>

    <script>
        // Simulate timer countdown
        let seconds = 0;
        setInterval(() => {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            const timerElement = document.getElementById('timer');
            if (timerElement) {
                timerElement.textContent = 
                    String(minutes).padStart(2, '0') + ':' + 
                    String(secs).padStart(2, '0');
            }
            seconds++;
            if (seconds > 3599) seconds = 0; // Reset after 1 hour
        }, 1000);
    </script>
</body>
</html>
