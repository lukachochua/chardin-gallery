<nav class="navbar bg-gray-800 shadow-lg" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-black hover:text-blue-400 transition-smooth">
                    Chardin Gallery
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('artworks.index') }}" class="nav-link">Artworks</a>
                <a href="{{ route('artists.index') }}" class="nav-link">Artists</a>
                <a href="#" class="nav-link">Exhibitions</a>
                <a href="#" class="nav-link">Blog</a>
            </div>

            <!-- Desktop Login/Register -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-smooth">Register</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button type="button" class="text-white hover:text-gray-300 focus:outline-none"
                    aria-label="Toggle menu" @click="open = !open">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" x-show="open" x-transition>
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('artworks.index') }}"
                    class="block text-white hover:text-gray-300 font-medium transition-smooth">Artworks</a>
                <a href="{{ route('artists.index') }}"
                    class="block text-white hover:text-gray-300 font-medium transition-smooth">Artists</a>
                <a href="#"
                    class="block text-white hover:text-gray-300 font-medium transition-smooth">Exhibitions</a>
                <a href="#" class="block text-white hover:text-gray-300 font-medium transition-smooth">Blog</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="block text-white hover:text-gray-300 font-medium transition-smooth">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="block text-white hover:text-gray-300 font-medium transition-smooth">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block text-white hover:text-gray-300 font-medium transition-smooth">Login</a>
                    <a href="{{ route('register') }}"
                        class="block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-smooth">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
