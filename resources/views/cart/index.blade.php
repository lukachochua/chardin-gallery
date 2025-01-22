<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-light mb-8">Your Cart</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($cart->items->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <p class="text-gray-600 text-lg mb-6">Your cart is currently empty</p>
                <a href="{{ route('artworks.index') }}"
                    class="inline-block px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-all duration-300">
                    Discover Artworks
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @foreach ($cart->items as $item)
                    <div class="flex items-center p-6 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <a href="{{ route('artworks.show', $item->artwork) }}" class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->artwork->image) }}" alt="{{ $item->artwork->title }}"
                                class="w-32 h-32 object-cover rounded-lg shadow-sm">
                        </a>

                        <div class="ml-6 flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-xl font-light">
                                        <a href="{{ route('artworks.show', $item->artwork) }}"
                                            class="hover:text-gray-600">
                                            {{ $item->artwork->title }}
                                        </a>
                                    </h2>
                                    <p class="text-gray-600 text-sm mt-1">{{ $item->artwork->artist->name }}</p>
                                </div>

                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"
                                        title="Remove item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center">
                                    <form action="{{ route('cart.update', $item) }}" method="POST"
                                        class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}"
                                            min="1" max="{{ $item->artwork->stock + $item->quantity }}"
                                            class="w-20 px-3 py-1 border border-gray-300 rounded-md text-center">
                                        <button type="submit"
                                            class="ml-2 text-sm text-gray-600 hover:text-black transition-colors">
                                            Update
                                        </button>
                                    </form>
                                </div>

                                <div class="text-right">
                                    <p class="text-lg font-light">
                                        ${{ number_format($item->artwork->price * $item->quantity, 2) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        ${{ number_format($item->artwork->price, 2) }} each
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-end items-center">
                        <div class="text-right mr-8">
                            <p class="text-lg text-gray-600">Total:</p>
                            <p class="text-2xl font-light">
                                ${{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->artwork->price), 2) }}
                            </p>
                        </div>
                        <a href="{{ route('checkout.index') }}"
                            class="px-8 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-all duration-300">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
