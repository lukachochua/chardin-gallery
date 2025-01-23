<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Order Details</h2>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
        </div>

        <!-- Order Details -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Order ID</p>
                            <p class="text-sm text-gray-900">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}"
                                class="mt-2">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach (['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                        <option value="{{ $status }}"
                                            {{ $order->status === $status ? 'selected' : '' }}>
                                            <span
                                                class="
                                                {{ $status === 'pending'
                                                    ? 'text-yellow-800 bg-yellow-100'
                                                    : ($status === 'processing'
                                                        ? 'text-blue-800 bg-blue-100'
                                                        : ($status === 'shipped'
                                                            ? 'text-indigo-800 bg-indigo-100'
                                                            : ($status === 'completed'
                                                                ? 'text-green-800 bg-green-100'
                                                                : ($status === 'cancelled'
                                                                    ? 'text-red-800 bg-red-100'
                                                                    : '')))) }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="text-sm text-gray-900">${{ number_format($order->total, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-sm text-gray-900">{{ $order->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Shipping Address</p>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Artwork
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="{{ asset('storage/' . $item->artwork->image) }}"
                                                alt="{{ $item->artwork->title }}"
                                                class="w-16 h-16 object-cover rounded-lg">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->artwork->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->artwork->artist->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${{ number_format($item->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            ${{ number_format($item->price * $item->quantity, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
