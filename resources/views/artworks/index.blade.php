<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <!-- Filters -->
        <div class="mb-16">
            <form action="{{ route('artworks.index') }}" method="GET" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Artist Filter -->
                    <div>
                        <select name="artist" id="artist"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                            <option value="">All Artists</option>
                            @foreach ($artists as $artist)
                                <option value="{{ $artist->id }}"
                                    {{ request('artist') == $artist->id ? 'selected' : '' }}>
                                    {{ $artist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select name="category" id="category"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <input type="number" name="min_price" id="min_price" placeholder="Min Price"
                            value="{{ request('min_price') }}"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                    </div>
                    <div>
                        <input type="number" name="max_price" id="max_price" placeholder="Max Price"
                            value="{{ request('max_price') }}"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                    </div>
                </div>

                <!-- Search and Submit -->
                <div class="flex justify-between items-center">
                    <input type="text" name="search" id="search" placeholder="Search artworks"
                        value="{{ request('search') }}"
                        class="w-64 bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                    <button type="submit"
                        class="px-8 py-2 bg-black text-white text-sm hover:bg-gray-800 transition-smooth">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Artwork Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach ($artworks as $artwork)
                <a href="{{ route('artworks.show', $artwork) }}" class="group">
                    <div class="space-y-4">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-light">{{ $artwork->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $artwork->artist->name }}</p>
                            <p class="text-sm">{{ $artwork->year_created }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $artworks->links() }}
        </div>
    </div>
</x-layouts.app>
