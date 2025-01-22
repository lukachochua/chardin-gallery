<x-layouts.app>
    <div class="container mx-auto px-4 py-8 text-center">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
            <svg class="w-20 h-20 text-green-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <h1 class="text-3xl font-bold mb-4">Thank You for Your Purchase!</h1>
            <p class="text-gray-600 mb-6">
                Your order (#{{ $order->id }}) has been successfully placed.
                A confirmation email has been sent to {{ $order->user->email }}.
            </p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('artworks.index') }}"
                    class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-smooth">
                    Continue Shopping
                </a>
                <a href="{{ route('home') }}"
                    class="border border-black text-black px-6 py-3 rounded-lg hover:bg-gray-50 transition-smooth">
                    Return Home
                </a>
            </div>
        </div>
    </div>
</x-layouts.apps>
