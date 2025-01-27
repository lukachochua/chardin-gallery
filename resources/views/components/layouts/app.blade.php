<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-items-route" content="{{ route('cart.items') }}">
    <meta name="cart-remove-route" content="{{ route('cart.remove', ['cartItem' => 'CART_ITEM_ID']) }}">
    <title>{{ config('app.name', 'Chardin Gallery') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <nav class="navbar shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('home') }}" class="text-2xl font-light tracking-wider text-black hover:text-gray-600">
                    CHARDIN
                </a>

                <x-toast />

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <x-nav.item href="{{ route('artworks.index') }}" active="{{ request()->routeIs('artworks.*') }}">
                        Artworks
                    </x-nav.item>
                    <x-nav.item href="{{ route('artists.index') }}" active="{{ request()->routeIs('artists.*') }}">
                        Artists
                    </x-nav.item>
                    <x-nav.item href="{{ route('exhibitions.index') }}"
                        active="{{ request()->routeIs('exhibitions.*') }}">
                        Exhibitions
                    </x-nav.item>
                </div>

                <!-- Auth Section -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    @auth
                        @if (auth()->user()->is_admin)
                            <x-nav.item href="{{ route('admin.dashboard') }}"
                                active="{{ request()->routeIs('admin.dashboard') }}">
                                Dashboard
                            </x-nav.item>
                        @else
                            <x-nav.cart-dropdown />
                        @endif
                        <x-auth.user />
                    @else
                        <x-auth.guest />
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="open = !open" class="p-2 text-black hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <x-nav.mobile-menu />
        </div>
    </nav>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <x-footer />
</body>

</html>
