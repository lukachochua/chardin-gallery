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

<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        Chardin Gallery
                    </a>
                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('artworks.index') }}" class="text-gray-700 hover:text-gray-900">Artworks</a>
                        <a href="#" class="text-gray-700 hover:text-gray-900">Artists</a>
                        <a href="#" class="text-gray-700 hover:text-gray-900">Exhibitions</a>
                        <a href="#" class="text-gray-700 hover:text-gray-900">Blog</a>
                    </div>
                </div>
                <!-- Login/Register Buttons -->
                <div class="flex items-center">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="ml-4">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="ml-4 text-gray-700 hover:text-gray-900">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <p class="text-gray-700">&copy; {{ date('Y') }} Chardin Gallery. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-700 hover:text-gray-900">Privacy Policy</a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
