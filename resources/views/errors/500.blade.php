<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            background: linear-gradient(135deg, #ffaa00, #ff6600);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(255, 170, 0, 0.5);
            animation: glow 2s ease-in-out infinite;
        }
        .error-title {
            font-size: 2rem;
            margin: 1rem 0;
            color: #ffaa00;
        }
        .error-message {
            font-size: 1.1rem;
            color: #a0aec0;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-home {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #00c8ff, #0080ff);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 200, 255, 0.3);
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 200, 255, 0.5);
        }
        @keyframes glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Server Error</h1>
        <p class="error-message">
            Something went wrong on our end. We're working to fix it. Please try again later.
        </p>
        <a href="{{ route('dashboard') }}" class="btn-home">Back to Dashboard</a>
    </div>
</body>
</html>
