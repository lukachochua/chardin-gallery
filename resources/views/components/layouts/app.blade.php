<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Chardin Gallery') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <nav class="navbar shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}"
                        class="text-2xl font-light tracking-wider text-black hover:text-gray-600 transition-smooth">
                        CHARDIN
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('artworks.index') }}"
                        class="nav-link {{ request()->routeIs('artworks.*') ? 'nav-active' : '' }}">
                        Artworks
                    </a>
                    <a href="{{ route('artists.index') }}"
                        class="nav-link {{ request()->routeIs('artists.*') ? 'nav-active' : '' }}">
                        Artists
                    </a>
                    <a href="{{ route('exhibitions.index') }}"
                        class="nav-link {{ request()->routeIs('exhibitions.*') ? 'nav-active' : '' }}">
                        Exhibitions
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="nav-link {{ request()->routeIs('cart.*') ? 'nav-active' : '' }} relative flex items-center"
                        onclick="event.preventDefault(); window.location.href = @auth '{{ route('cart.index') }}' @else '{{ route('login') }}' @endauth">
                        Cart
                        @auth
                            @php
                                $cartCount = optional(optional(auth()->user()->cart)->items)->count() ?? 0;
                            @endphp
                            @if ($cartCount > 0)
                                <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>
                </div>

                <!-- Desktop Login/Register -->
                <div class="hidden md:flex md:items-center md:space-x-6">
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="nav-link">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-black hover:bg-gray-800 transition-smooth">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" class="text-black hover:text-gray-600 focus:outline-none"
                        aria-label="Toggle menu" @click="open = !open">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden" x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('artworks.index') }}"
                        class="block py-2 text-base font-light {{ request()->routeIs('artworks.*') ? 'text-black' : 'text-gray-600' }}">
                        Artworks
                    </a>
                    <a href="{{ route('artists.index') }}"
                        class="block py-2 text-base font-light {{ request()->routeIs('artists.*') ? 'text-black' : 'text-gray-600' }}">
                        Artists
                    </a>
                    <a href="{{ route('exhibitions.index') }}"
                        class="block py-2 text-base font-light {{ request()->routeIs('exhibitions.*') ? 'text-black' : 'text-gray-600' }}">
                        Exhibitions
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="block py-2 text-base font-light {{ request()->routeIs('cart.*') ? 'text-black' : 'text-gray-600' }}"
                        onclick="event.preventDefault(); window.location.href = @auth '{{ route('cart.index') }}' @else '{{ route('login') }}' @endauth">
                        Cart
                        @auth
                            @php
                                $cartCount = optional(optional(auth()->user()->cart)->items)->count() ?? 0;
                            @endphp
                            @if ($cartCount > 0)
                                <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                            class="block py-2 text-base font-light {{ request()->routeIs('admin.dashboard') ? 'text-black' : 'text-gray-600' }}">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left py-2 text-base font-light text-gray-600">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 text-base font-light text-gray-600">Login</a>
                        <a href="{{ route('register') }}"
                            class="block mt-2 px-4 py-2 text-base font-light text-white bg-black text-center">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-gray-200 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <h3 class="text-lg font-medium">Contact</h3>
                    <p class="text-sm text-gray-600">info@chardingallery.com</p>
                    <p class="text-sm text-gray-600">+1 (555) 123-4567</p>
                </div>
                <div class="space-y-4">
                    <h3 class="text-lg font-medium">Location</h3>
                    <p class="text-sm text-gray-600">123 Art Street</p>
                    <p class="text-sm text-gray-600">New York, NY 10001</p>
                </div>
                <div class="space-y-4">
                    <h3 class="text-lg font-medium">Follow</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-black transition-smooth">Instagram</a>
                        <a href="#" class="text-gray-600 hover:text-black transition-smooth">Twitter</a>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-200 text-center text-sm text-gray-600">
                &copy; {{ date('Y') }} Chardin Gallery. All rights reserved.
            </div>
        </div>
    </footer>
</body>

</html>
