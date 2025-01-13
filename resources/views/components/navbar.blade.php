<nav class="bg-white shadow-lg" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}"
                    class="text-2xl font-bold text-gray-800 hover:text-gray-900 transition duration-300">
                    Chardin Gallery
                </a>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden sm:flex sm:space-x-8 sm:items-center">
                <a href="{{ route('artworks.index') }}"
                    class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                    Artworks
                </a>
                <a href="{{ route('artists.index') }}" class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                    Artists
                </a>
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                    Exhibitions
                </a>
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                    Blog
                </a>
            </div>

            <!-- Login/Register Buttons -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden">
                <button type="button" class="text-gray-700 hover:text-gray-900 focus:outline-none"
                    aria-label="Toggle menu" @click="open = !open">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="sm:hidden" x-show="open" x-transition>
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('artworks.index') }}"
                class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                Artworks
            </a>
            <a href="#" class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                Artists
            </a>
            <a href="#" class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                Exhibitions
            </a>
            <a href="#" class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                Blog
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}"
                    class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                    Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="block text-gray-700 hover:text-gray-900 font-medium transition duration-300">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>
