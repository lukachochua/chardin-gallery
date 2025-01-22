<x-layouts.app>
    <div class="container mx-auto px-4 py-8 text-center">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
            <svg class="w-20 h-20 text-yellow-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>

            <h1 class="text-3xl font-bold mb-4">Payment Cancelled</h1>
            <p class="text-gray-600 mb-6">
                Your payment was not completed. Your order has not been processed and you will not be charged.
            </p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('checkout.index') }}"
                    class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-smooth">
                    Try Again
                </a>
                <a href="{{ route('cart.index') }}"
                    class="border border-black text-black px-6 py-3 rounded-lg hover:bg-gray-50 transition-smooth">
                    Review Cart
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
