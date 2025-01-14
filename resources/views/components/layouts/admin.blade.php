<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chardin Gallery') }} - Admin</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-xl font-semibold text-gray-800 hover:text-gray-900 transition duration-150">
                                Chardin Gallery Admin
                            </a>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:flex sm:items-center sm:space-x-6">
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                            class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition duration-150">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.artists.index')" :active="request()->routeIs('admin.artists.*')"
                            class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition duration-150">
                            Artists
                        </x-nav-link>
                        <x-nav-link :href="route('admin.artworks.index')" :active="request()->routeIs('admin.artworks.*')"
                            class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition duration-150">
                            Artworks
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')"
                            class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition duration-150">
                            Categories
                        </x-nav-link>
                        <!-- Add the Exhibitions link -->
                        <x-nav-link :href="route('admin.exhibitions.index')" :active="request()->routeIs('admin.exhibitions.*')"
                            class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition duration-150">
                            Exhibitions
                        </x-nav-link>

                        <!-- Logout Button -->
                        <div class="flex items-center h-full ml-4 border-l border-gray-200 pl-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition duration-150">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>
