<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Artwork Catalog</h1>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form action="{{ route('artworks.index') }}" method="GET"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Artist Filter -->
                <div>
                    <label for="artist" class="block text-sm font-medium text-gray-700">Artist</label>
                    <select name="artist" id="artist" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
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
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price</label>
                    <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price</label>
                    <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>

                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2 lg:col-span-4">
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">Apply
                        Filters</button>
                </div>
            </form>
        </div>

        <!-- Artwork Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($artworks as $artwork)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('artworks.show', $artwork) }}">
                        <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}"
                            class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $artwork->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $artwork->artist->name }}</p>
                            <p class="text-lg font-bold text-gray-900">${{ number_format($artwork->price, 2) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $artworks->links() }}
        </div>
    </div>
</x-layouts.app>
