<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Expatriate Monitoring') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Global CSS files -->
    @stack('styles') <!-- For page-specific styles like Frappe Gantt -->

    <!-- Vite compiled assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

<div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-lightblue-100 text-white flex-shrink-0">
        <div class="p-6 flex flex-col items-center">
            <!-- Logo -->
            <img src="{{ asset('images/toyoflex.png') }}" alt="Toyoflex Logo" class="w-24 mb-6">

            <!-- Navigation -->
            <nav class="mt-10 space-y-4 w-full">
                @php $user = auth()->user(); @endphp

                @if($user->isGAStaff())
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-item">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('gantt.index')" :active="request()->routeIs('gantt.*')" class="nav-item">
                        {{ __('Gantt Chart') }}
                    </x-nav-link>

                    <x-nav-link :href="route('actual.index')" :active="request()->routeIs('actual.*')" class="nav-item">
                        {{ __('Actual Progress Entry') }}
                    </x-nav-link>

                    <x-nav-link :href="route('process.index')" :active="request()->routeIs('process.*')" class="nav-item">
                        {{ __('Process Management') }}
                    </x-nav-link>

                    <x-nav-link :href="route('applications.index')" :active="request()->routeIs('applications.*')" class="nav-item">
                        {{ __('Application Management') }}
                    </x-nav-link>

                @elseif($user->isAdminExpatriate() || $user->isExpatriate())
                    <x-nav-link :href="route('gantt.index')" :active="request()->routeIs('gantt.*')" class="nav-item">
                        {{ __('Gantt Chart') }}
                    </x-nav-link>
                @endif
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

        <!-- Topbar -->
        <header class="bg-lightblue-300 text-white p-4 flex justify-between items-center">
            <div class="flex items-center space-x-4 ml-auto">
                <!-- Date & Time -->
                <div id="datetime" class="text-sm font-semibold"></div>

                <!-- User Name -->
                <span class="text-sm">{{ $user->name }}</span>

                <!-- User Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-white text-sm" aria-label="User Menu">
                            <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

<!-- Date & Time Script -->
<script>
    function updateTime() {
        const today = new Date();
        const day = today.toLocaleDateString('en-US', { weekday: 'long' });
        const dateStr = today.getFullYear() + "-" +
                        String(today.getMonth() + 1).padStart(2, '0') + "-" +
                        String(today.getDate()).padStart(2, '0');
        const timeStr = today.toLocaleTimeString('en-GB', { hour12: false });
        document.getElementById("datetime").innerHTML = `${day} | ${dateStr} | ${timeStr}`;
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>

<!-- Frappe Gantt Styles & Scripts -->
@stack('styles')
@stack('scripts')

</body>
</html>
