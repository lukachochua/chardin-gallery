<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chardin Gallery') }} - Admin</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <x-navbar />

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
