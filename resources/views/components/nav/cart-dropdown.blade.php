@php
    $cartCount = optional(optional(auth()->user()->cart)->items)->sum('quantity') ?? 0;
@endphp

<div class="relative" x-data="cart({{ $cartCount }})" x-cloak>
    <div @mouseenter="openCart()" @mouseleave="closeCart()" class="relative">
        <button class="px-4 py-2 hover:bg-gray-100">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span
                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center border-2 border-white"
                    x-text="cartCount" :style="cartCount > 0 ? 'display: flex' : 'display: none'"></span>
            </div>
        </button>

        <!-- Cart Dropdown -->
        <div class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-show="cartOpen" x-transition @mouseenter="cancelClose()" @mouseleave="closeCart()">
            <div class="p-4">
                <h3 class="text-lg font-light mb-4">Your Cart</h3>
                <template x-if="loading">
                    <div class="text-center py-4">
                        <svg class="animate-spin h-6 w-6 text-black mx-auto" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </template>

                <template x-if="!loading && cartItems.length === 0">
                    <p class="text-gray-500 text-sm">Your cart is empty</p>
                </template>

                <template x-if="!loading && cartItems.length > 0">
                    <div class="space-y-4">
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            <template x-for="item in cartItems" :key="item.id">
                                <div class="py-3">
                                    <div class="flex gap-4 items-start">
                                        <img :src="item.artwork.image" class="w-16 h-16 object-cover rounded"
                                            :alt="item.artwork.title">
                                        <div class="flex-1">
                                            <h4 class="font-light" x-text="item.artwork.title"></h4>
                                            <p class="text-sm text-gray-500" x-text="item.artwork.artist.name"></p>
                                            <div class="flex justify-between items-center mt-2">
                                                <div class="text-sm">
                                                    <span x-text="`Qty: ${item.quantity}`"></span>
                                                    <button @click.prevent="removeItem(item.id)"
                                                        class="text-red-500 hover:text-red-700 ml-2">
                                                        Remove
                                                    </button>
                                                </div>
                                                <p class="text-sm font-medium"
                                                    x-text="`$${(item.artwork.price * item.quantity).toFixed(2)}`"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <a href="{{ route('cart.index') }}"
                            class="block w-full text-center px-4 py-2 bg-black text-white hover:bg-gray-800 transition-smooth">
                            View Cart & Checkout
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
