<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Artwork Image -->
            <div class="aspect-w-4 aspect-h-3">
                <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}"
                    class="w-full h-full object-cover rounded-lg shadow-md">
            </div>

            <!-- Artwork Details -->
            <div class="space-y-8">
                <div class="space-y-2">
                    <h1 class="text-3xl font-light">{{ $artwork->title }}</h1>
                    <p class="text-lg text-gray-600">{{ $artwork->artist->name }}</p>
                    <p class="text-sm text-gray-500">{{ $artwork->year_created }}</p>
                </div>

                <div class="space-y-6">
                    <p class="text-gray-700 leading-relaxed">{{ $artwork->description }}</p>

                    <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Medium</p>
                            <p class="font-medium">{{ $artwork->medium }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Dimensions</p>
                            <p class="font-medium">{{ $artwork->dimensions }}</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-2xl font-light">${{ number_format($artwork->price, 2) }}</p>
                    </div>

                    @if ($artwork->is_available)
                        <!-- Updated Add to Cart Form -->
                        <div x-data="{ submitting: false, quantity: 1 }" class="pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-4">
                                <input type="number" x-model="quantity" min="1" max="{{ $artwork->stock }}"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-md text-center"
                                    :disabled="submitting">
                                <button
                                    @click.prevent="
                                        submitting = true;
                                        fetch('{{ route('cart.add', $artwork) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({ quantity: quantity })
                                        })
                                        .then(response => {
                                            if (!response.ok) throw new Error('Failed to add item');
                                            return response.json();
                                        })
                                        .then(data => {
                                            $dispatch('cart-updated', { 
                                                message: data.message,
                                                cartCount: data.cart_count
                                            });
                                            document.querySelectorAll('.cart-count').forEach(el => {
                                                el.textContent = data.cart_count;
                                                el.style.display = data.cart_count > 0 ? 'flex' : 'none';
                                            });
                                        })
                                        .catch(error => {
                                            $dispatch('cart-updated', { message: error.message });
                                        })
                                        .finally(() => submitting = false);
                                    "
                                    class="flex-1 px-8 py-3 bg-black text-white text-sm hover:bg-gray-800 transition-all"
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
                            </div>
                        </div>
                    @else
                        <p class="pt-6 text-gray-500">Currently unavailable</p>
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
                        <div class="group">
                            <a href="{{ route('artworks.show', $related) }}" class="block">
                                <div class="space-y-4">
                                    <div class="aspect-w-4 aspect-h-3 overflow-hidden rounded-lg">
                                        <img src="{{ asset('storage/' . $related->image) }}"
                                            alt="{{ $related->title }}"
                                            class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                                    </div>
                                    <div class="space-y-2">
                                        <h3 class="text-lg font-light">{{ $related->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $related->artist->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $related->year_created }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
