\<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Confirm Password</title>

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
            <h1 class="auth-title">Confirm Password</h1>

            <!-- Description -->
            <div class="auth-message">
                This is a secure area of the application. Please confirm your password before continuing.
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password Input -->
                <div class="auth-input-group mt-4">
                    <input id="password" type="password" name="password" placeholder="Password" class="auth-input" required autocomplete="current-password">
                    <button type="button" class="auth-toggle-btn" onclick="togglePassword()"></button>
                    @error('password')
                        <div class="auth-error-row">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="auth-button mt-4">Confirm</button>

                <!-- Optional: Forgot password link -->
                <div class="auth-forgot mt-2 text-right">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Live Date/Time -->
        <div class="auth-datetime" id="live-datetime"></div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }

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
