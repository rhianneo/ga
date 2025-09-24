<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Verify Email</title>

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
            <h1 class="auth-title">Verify Your Email</h1>

            <!-- Instruction -->
            <p class="auth-message text-gray-700 mb-4 text-sm">
                Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent you. 
                If you didn't receive the email, we will gladly send you another.
            </p>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="auth-message text-green-600 mb-4 text-sm">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div class="flex flex-col gap-3">
                <!-- Resend Verification Email -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="auth-button w-full">
                        Resend Verification Email
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="auth-button w-full bg-gray-600 hover:bg-gray-700">
                        Log Out
                    </button>
                </form>
            </div>
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
