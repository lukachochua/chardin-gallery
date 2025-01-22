<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Your Shopping Cart</h1>

        @if ($cart->items->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 text-lg mb-4">Your cart is currently empty</p>
                <a href="{{ route('artworks.index') }}"
                    class="inline-block bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-smooth">
                    Browse Artworks
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6">
                @foreach ($cart->items as $item)
                    <div class="flex items-center border-b pb-4 mb-4">
                        <img src="{{ $item->artwork->getFirstMediaUrl('artwork_images', 'thumb') }}"
                            alt="{{ $item->artwork->title }}" class="w-24 h-24 object-cover rounded">

                        <div class="ml-6 flex-1">
                            <h3 class="text-xl font-semibold">
                                <a href="{{ route('artworks.show', $item->artwork) }}" class="hover:text-gray-600">
                                    {{ $item->artwork->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600">{{ $item->artwork->artist->name }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-lg font-bold">${{ number_format($item->artwork->price, 2) }}</span>
                                <span class="mx-4">×</span>
                                <span class="text-gray-600">{{ $item->quantity }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach

                <div class="mt-6 text-right">
                    <p class="text-2xl font-bold mb-4">
                        Total: ${{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->artwork->price), 2) }}
                    </p>
                    <a href="{{ route('checkout.index') }}"
                        class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-smooth">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
