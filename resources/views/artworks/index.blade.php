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
                <div class="group relative">
                    <a href="{{ route('artworks.show', $artwork) }}" class="block">
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

                    @if ($artwork->is_available)
                        <form action="{{ route('cart.add', $artwork) }}" method="POST" class="mt-4"
                            x-data="{ submitting: false }" @submit.prevent="submitting = true; $event.target.submit()">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="w-full px-4 py-2 bg-black text-white text-sm hover:bg-gray-800 transition-all"
                                :disabled="submitting">
                                <span x-show="!submitting">Add to Cart</span>
                                <span x-show="submitting" class="flex items-center justify-center">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $artworks->links() }}
        </div>
    </div>
</x-layouts.app>
