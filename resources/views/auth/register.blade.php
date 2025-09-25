<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Register</title>

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
            <h1 class="auth-title">Create Your Account</h1>

            <!-- Session Status -->
            @if(session('status'))
                <div class="auth-message">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="auth-input-group">
                    <input id="name" type="text" name="name" placeholder="Full Name" class="auth-input" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="auth-error-row">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="auth-input-group">
                    <input id="email" type="email" name="email" placeholder="Email" class="auth-input" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="auth-error-row">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="auth-input-group">
                    <input id="password" type="password" name="password" placeholder="Password" class="auth-input" required>
                    <button type="button" class="auth-toggle-btn" onclick="togglePassword('password')"></button>
                    @error('password')
                        <div class="auth-error-row">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="auth-input-group">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" class="auth-input" required>
                    <button type="button" class="auth-toggle-btn" onclick="togglePassword('password_confirmation')"></button>
                    @error('password_confirmation')
                        <div class="auth-error-row">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Already Registered -->
                <div class="auth-footer text-left pl-2">
                    Already have an account? <a href="{{ route('login') }}">Login</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="auth-button">Register</button>
            </form>
        </div>

        <!-- Live Date/Time -->
        <div class="auth-datetime" id="live-datetime"></div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle password visibility for multiple inputs
        function togglePassword(id) {
            const input = document.getElementById(id);
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
        updateDateTime(); // initial call
    </script>
</body>
</html>
