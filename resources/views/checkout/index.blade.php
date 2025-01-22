<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Shipping Details</h2>
                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" required
                                class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
                                value="{{ auth()->user()->name }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required
                                class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
                                value="{{ auth()->user()->email }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                            <textarea name="shipping_address" required rows="4"
                                class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Order Summary</h2>

                <div class="space-y-4">
                    @foreach ($cart->items as $item)
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium">{{ $item->artwork->title }}</h3>
                                <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            </div>
                            <span
                                class="font-medium">${{ number_format($item->quantity * $item->artwork->price, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t mt-6 pt-6">
                    <div class="flex justify-between text-xl font-bold">
                        <span>Total:</span>
                        <span>${{ number_format($cartTotal, 2) }}</span>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Payment Details</h3>
                    <div id="card-element" class="border rounded-lg p-3"></div>
                    <button type="submit" form="checkout-form"
                        class="w-full bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-smooth mt-6">
                        Complete Purchase
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
