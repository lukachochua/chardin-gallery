<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Artwork Image -->
            <div class="aspect-w-4 aspect-h-3">
                <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}"
                    class="w-full h-full object-cover">
            </div>

            <!-- Artwork Details -->
            <div class="space-y-8">
                <div class="space-y-2">
                    <h1 class="text-3xl font-light">{{ $artwork->title }}</h1>
                    <p class="text-lg">{{ $artwork->artist->name }}</p>
                    <p class="text-sm text-gray-600">{{ $artwork->year_created }}</p>
                </div>

                <div class="space-y-4 text-sm">
                    <p class="leading-relaxed">{{ $artwork->description }}</p>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div>
                            <p class="text-gray-600">Medium</p>
                            <p>{{ $artwork->medium }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Dimensions</p>
                            <p>{{ $artwork->dimensions }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-gray-600">Price</p>
                        <p class="text-lg">${{ number_format($artwork->price, 2) }}</p>
                    </div>

                    @if ($artwork->is_available)
                        <button
                            class="w-full px-8 py-3 bg-black text-white text-sm hover:bg-gray-800 transition-smooth">
                            Inquire
                        </button>
                    @else
                        <p class="text-gray-600">Sold</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Artworks -->
        @if ($relatedArtworks->count() > 0)
            <div class="mt-24">
                <h2 class="text-2xl font-light mb-8">Related Works</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    @foreach ($relatedArtworks as $related)
                        <a href="{{ route('artworks.show', $related) }}" class="group">
                            <div class="space-y-4">
                                <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-light">{{ $related->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $related->artist->name }}</p>
                                    <p class="text-sm">{{ $related->year_created }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
