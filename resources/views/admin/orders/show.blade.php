<x-layouts.admin>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">Order #{{ $order->id }}</h2>
                <p class="text-gray-600">Placed on {{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold">Total: ${{ number_format($order->total, 2) }}</p>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="mt-2">
                    @csrf
                    @method('PUT')
                    <select name="status" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach (['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Items -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex items-center border-b pb-4">
                            <img src="{{ $item->artwork->getFirstMediaUrl('artwork_images', 'thumb') }}"
                                class="w-16 h-16 object-cover rounded">
                            <div class="ml-4">
                                <h4 class="font-medium">{{ $item->artwork->title }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Customer Details -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Customer Information</h3>
                <div class="space-y-2">
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Shipping Address:</strong></p>
                    <p class="whitespace-pre-wrap bg-gray-50 p-3 rounded-md mt-2">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
