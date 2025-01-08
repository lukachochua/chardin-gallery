<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Artwork Image -->
            <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}" class="w-full h-96 object-cover">

            <!-- Artwork Details -->
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $artwork->title }}</h1>
                <p class="text-lg text-gray-600 mb-2">By {{ $artwork->artist->name }}</p>
                <p class="text-xl font-bold text-gray-900 mb-4">${{ number_format($artwork->price, 2) }}</p>
                <p class="text-gray-700 mb-4">{{ $artwork->description }}</p>

                <!-- Additional Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Dimensions:</p>
                        <p class="text-gray-800">{{ $artwork->dimensions }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Medium:</p>
                        <p class="text-gray-800">{{ $artwork->medium }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Year Created:</p>
                        <p class="text-gray-800">{{ $artwork->year_created }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Availability:</p>
                        <p class="text-gray-800">{{ $artwork->is_available ? 'Available' : 'Sold' }}</p>
                    </div>
                </div>

                <!-- Related Artworks -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Related Artworks</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($relatedArtworks as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <a href="{{ route('artworks.show', $related) }}">
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $related->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $related->artist->name }}</p>
                                    <p class="text-lg font-bold text-gray-900">${{ number_format($related->price, 2) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>