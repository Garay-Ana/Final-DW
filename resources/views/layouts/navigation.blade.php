<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto text-gray-900" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-6">
                    {{-- Removed the Dashboard/Welcome navigation link as per user request --}}
                    {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Welcome') }}
                    </x-nav-link> --}}
                </div>

                {{-- @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="hidden sm:flex space-x-6">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Panel Admin') }}
                    </x-nav-link>
                </div>
                @endif --}}
            </div>

            <!-- Settings Dropdown -->
            @if(auth()->check() && auth()->user()->role !== 'admin')
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div>
                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profile Link -->
                        <x-dropdown-link :href="route('profile.show')">
                            {{ __('Perfil de Usuario') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endif

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden hidden bg-white border-t border-gray-200">
        <div class="pt-4 pb-3 space-y-1 px-4">
            {{-- Removed the Dashboard/Welcome navigation link as per user request --}}
            {{-- <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Welcome') }}
            </x-responsive-nav-link> --}}

            {{-- @if(auth()->check() && auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Panel Admin') }}
            </x-responsive-nav-link>
            @endif --}}
        </div>

        <!-- Responsive Settings Options -->
<div class="pt-4 pb-4 border-t border-gray-100 px-4 bg-white">
    <div class="mb-4 text-center">
        <div class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</div>
        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
    </div>

    <div class="space-y-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                class="block w-full text-center text-red-600 hover:text-red-700 transition"
                onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Cerrar sesión') }}
            </x-responsive-nav-link>
        </form>
    </div>
</div>

</nav>
