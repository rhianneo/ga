<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Forgot Password</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-container" style="background-image: url('{{ asset('images/background.jpg') }}');">

        <!-- Logos -->
        <img src="{{ asset('images/asahi.png') }}" class="auth-logo auth-top-left" alt="Asahi Logo">
        <img src="{{ asset('images/toyoflex.png') }}" class="auth-logo auth-top-right" alt="Toyo Logo">

        <!-- Auth Card -->
        <div class="auth-card">

            <h1 class="auth-title">Forgot Password</h1>

            <!-- Instruction Text -->
            <p class="auth-footer" style="margin-bottom: 12px; font-size: 13px; color: #1f2937;">
                Forgot your password? No problem. Enter your email and we'll send a password reset link.
            </p>

            <!-- Session Status -->
            @if(session('status'))
                <div class="auth-message">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="auth-input-group">
                    <input id="email" type="email" name="email" placeholder="Email" class="auth-input" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="auth-error-row">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="auth-button">
                    Email Password Reset Link
                </button>

                <!-- Back to Login -->
                <div class="auth-footer" style="margin-top: 12px; font-size: 13px;">
                    Remembered your password?
                    <a href="{{ route('login') }}">Login</a>
                </div>

            </form>
        </div>

        <!-- Live Date/Time -->
        <div class="auth-datetime" id="live-datetime"></div>
    </div>

    <!-- Scripts -->
    <script>
        // Live date & time
        function updateDateTime() {
            const dtElem = document.getElementById('live-datetime');
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: '2-digit', day: '2-digit' };
            const dateStr = now.toLocaleDateString('en-US', options);
            const timeStr = now.toLocaleTimeString('en-GB', { hour12: false });
            dtElem.textContent = `${dateStr.split(',')[0]} | ${dateStr.split(',')[1].trim()} | ${timeStr}`;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call
    </script>
</body>
</html>
        