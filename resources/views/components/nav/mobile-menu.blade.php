<div class="md:hidden" x-show="open" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1">
    <div class="px-2 pt-2 pb-3 space-y-1">
        <x-nav.item href="{{ route('artworks.index') }}" active="{{ request()->routeIs('artworks.*') }}">
            Artworks
        </x-nav.item>
        <x-nav.item href="{{ route('artists.index') }}" active="{{ request()->routeIs('artists.*') }}">
            Artists
        </x-nav.item>
        <x-nav.item href="{{ route('exhibitions.index') }}" active="{{ request()->routeIs('exhibitions.*') }}">
            Exhibitions
        </x-nav.item>

        @auth
            @if (auth()->user()->is_admin)
                <x-nav.item href="{{ route('admin.dashboard') }}" active="{{ request()->routeIs('admin.dashboard') }}">
                    Dashboard
                </x-nav.item>
            @else
                @php
                    $cartCount = optional(optional(auth()->user()->cart)->items)->sum('quantity') ?? 0;
                @endphp
                <a href="{{ route('cart.index') }}" class="block px-4 py-2 hover:bg-gray-100">
                    <div class="relative flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-2">Cart</span>
                        <span
                            class="ml-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                            style="display: {{ $cartCount > 0 ? 'flex' : 'none' }}"
                            @cart-updated.window="$el.style.display = $event.detail.cartCount > 0 ? 'flex' : 'none'; $el.textContent = $event.detail.cartCount;">
                            {{ $cartCount }}
                        </span>
                    </div>
                </a>
            @endif
            <x-auth.user />
        @else
            <x-auth.guest />
        @endauth
    </div>
</div>
