<nav x-data="{ open: false }" class="bg-lightblue-100 border-b border-lightblue-300 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/toyoflex.png') }}" class="h-10 w-auto" alt="Toyoflex Logo">
                    </a>
                </div>
                <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                    @php $user = auth()->user(); @endphp
                    @if($user->isGAStaff())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                        <x-nav-link :href="route('gantt.index')" :active="request()->routeIs('gantt.*')">Gantt Chart</x-nav-link>
                        <x-nav-link :href="route('applications.index')" :active="request()->routeIs('applications.*')">Application Management</x-nav-link>
                        <x-nav-link :href="route('actual.index')" :active="request()->routeIs('actual.*')">Actual Progress Entry</x-nav-link>
                        <x-nav-link :href="route('process.index')" :active="request()->routeIs('process.*')">Process Management</x-nav-link>

                    @elseif($user->isAdminExpatriate())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                        <x-nav-link :href="route('gantt')" :active="request()->routeIs('gantt')">Gantt Chart</x-nav-link>
                    @elseif($user->isExpatriate())
                        <x-nav-link :href="route('gantt')" :active="request()->routeIs('gantt')">Gantt Chart</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-800">
                            <div>{{ $user->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
