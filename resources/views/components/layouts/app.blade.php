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
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}"
                        class="text-2xl font-light tracking-wider text-black hover:text-gray-600 transition-smooth">
                        CHARDIN
                    </a>
                </div>

                <!-- Toast Notification -->
                <div class="fixed top-4 right-4 z-50" x-data="{ show: false, message: '' }" x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform translate-y-2"
                    @cart-updated.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)">
                    <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-lg" x-text="message"></div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('artworks.index') }}"
                        class="nav-link px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('artworks.*') ? 'nav-active' : '' }}">
                        Artworks
                    </a>
                    <a href="{{ route('artists.index') }}"
                        class="nav-link px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('artists.*') ? 'nav-active' : '' }}">
                        Artists
                    </a>
                    <a href="{{ route('exhibitions.index') }}"
                        class="nav-link px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('exhibitions.*') ? 'nav-active' : '' }}">
                        Exhibitions
                    </a>
                </div>

                <!-- Desktop Auth + Cart/Dashboard -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                                Dashboard
                            </a>
                        @else
                            @php
                                $cartCount = optional(optional(auth()->user()->cart)->items)->sum('quantity') ?? 0;
                            @endphp
                            <div class="relative" x-data="cart({{ $cartCount }})" x-cloak>
                                <div @mouseenter="openCart()" @mouseleave="closeCart()" class="relative">
                                    <button class="px-4 py-2 hover:bg-gray-100">
                                        <div class="relative">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span
                                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center border-2 border-white cart-count"
                                                x-text="cartCount"
                                                :style="cartCount > 0 ? 'display: flex' : 'display: none'">
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Cart Dropdown -->
                                    <div class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                                        x-show="cartOpen" x-transition x-cloak @mouseenter="cancelClose()"
                                        @mouseleave="closeCart()">
                                        <div class="p-4">
                                            <h3 class="text-lg font-light mb-4">Your Cart</h3>

                                            <template x-if="loading">
                                                <div class="text-center py-4">
                                                    <svg class="animate-spin h-6 w-6 text-black mx-auto"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </template>

                                            <template x-if="!loading && cartItems.length === 0">
                                                <p class="text-gray-500 text-sm">Your cart is empty</p>
                                            </template>

                                            <template x-if="!loading && cartItems.length > 0">
                                                <div class="space-y-4">
                                                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                                        <template x-for="item in cartItems" :key="item.id">
                                                            <div class="py-3">
                                                                <div class="flex gap-4 items-start">
                                                                    <img :src="item.artwork.image"
                                                                        class="w-16 h-16 object-cover rounded"
                                                                        :alt="item.artwork.title">
                                                                    <div class="flex-1">
                                                                        <h4 class="font-light" x-text="item.artwork.title">
                                                                        </h4>
                                                                        <p class="text-sm text-gray-500"
                                                                            x-text="item.artwork.artist.name"></p>
                                                                        <div class="flex justify-between items-center mt-2">
                                                                            <div class="text-sm">
                                                                                <span
                                                                                    x-text="`Qty: ${item.quantity}`"></span>
                                                                                <button @click.prevent="removeItem(item.id)"
                                                                                    class="text-red-500 hover:text-red-700 ml-2">
                                                                                    Remove
                                                                                </button>
                                                                            </div>
                                                                            <p class="text-sm font-medium"
                                                                                x-text="`$${(item.artwork.price * item.quantity).toFixed(2)}`">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <a href="{{ route('cart.index') }}"
                                                        class="block w-full text-center px-4 py-2 bg-black text-white hover:bg-gray-800 transition-smooth">
                                                        View Cart & Checkout
                                                    </a>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="nav-link px-4 py-2 hover:bg-gray-100">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link px-4 py-2 hover:bg-gray-100">Login</a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-black hover:bg-gray-800 transition-smooth">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" class="text-black hover:text-gray-600 focus:outline-none p-2"
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
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('artworks.index') }}"
                        class="block py-2 px-4 hover:bg-gray-100 {{ request()->routeIs('artworks.*') ? 'text-black' : 'text-gray-600' }}">
                        Artworks
                    </a>
                    <a href="{{ route('artists.index') }}"
                        class="block py-2 px-4 hover:bg-gray-100 {{ request()->routeIs('artists.*') ? 'text-black' : 'text-gray-600' }}">
                        Artists
                    </a>
                    <a href="{{ route('exhibitions.index') }}"
                        class="block py-2 px-4 hover:bg-gray-100 {{ request()->routeIs('exhibitions.*') ? 'text-black' : 'text-gray-600' }}">
                        Exhibitions
                    </a>

                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                                class="block py-2 px-4 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'text-black' : 'text-gray-600' }}">
                                Dashboard
                            </a>
                        @else
                            @php
                                $cartCount = optional(optional(auth()->user()->cart)->items)->sum('quantity') ?? 0;
                            @endphp
                            <a href="{{ route('cart.index') }}" class="block py-2 px-4 hover:bg-gray-100 items-center">
                                <div class="relative mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span
                                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center border-2 border-white cart-count"
                                        style="display: {{ $cartCount > 0 ? 'flex' : 'none' }}"
                                        @cart-updated.window="
                                            $el.style.display = $event.detail.cartCount > 0 ? 'flex' : 'none';
                                            $el.textContent = $event.detail.cartCount;
                                        ">
                                        {{ $cartCount }}
                                    </span>
                                </div>
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left py-2 px-4 hover:bg-gray-100 text-gray-600">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 px-4 hover:bg-gray-100 text-gray-600">Login</a>
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
