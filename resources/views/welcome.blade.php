<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ToyoFlex Expat Monitoring System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <style>
        /* Body & Page Background */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff, #e0f0ff);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative; /* needed for absolute footer */
        }

        /* Main Landing Card */
        .landing-container {
            text-align: center;
            padding: 40px 25px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            max-width: 500px;
            width: 90%;
            transition: all 0.3s ease;
        }

        .landing-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        /* Logo */
        .landing-logo {
            width: 120px;
            margin: 0 auto 25px;
            display: block;
        }

        /* Title */
        h1 {
            font-family: 'Merriweather', serif;
            font-size: 25px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
        }

        /* Buttons */
        .landing-buttons a {
            display: inline-block;
            margin: 0 10px;
            padding: 14px 28px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-login {
            background-color: #1f2937;
            color: #ffffff;
        }

        .btn-login:hover {
            background-color: #6fb9e4;
        }

        .btn-register {
            background-color: #ffffff;
            border: 2px solid #1f2937;
            color: #1f2937;
        }

        .btn-register:hover {
            background-color: #e0f0ff;
        }

        /* Footer fixed at bottom */
        .footer {
            position: absolute;
            bottom: 20px; /* distance from bottom */
            width: 100%;
            font-size: 14px;
            color: #6b7280; /* gray-500 */
            text-align: center;
        }

    </style>
</head>
<body>

    <!-- Landing Card -->
    <div class="landing-container">
        <!-- Logo Centered -->
        <img src="{{ asset('images/toyoflex.png') }}" alt="ToyoFlex Logo" class="landing-logo">

        <!-- Title -->
        <h1>Welcome to the Expatriate Monitoring System</h1>

        <!-- Buttons -->
        <div class="landing-buttons">
            <a href="{{ route('login') }}" class="btn-login">Log In</a>
            <a href="{{ route('register') }}" class="btn-register">Register</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2025 ToyoFlex. All rights reserved.
    </div>

</body>
</html>
