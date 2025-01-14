<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <!-- Filters -->
        <div class="mb-16">
            <form action="{{ route('artists.index') }}" method="GET" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name Search -->
                    <div>
                        <input type="text" name="search" id="search" placeholder="Search artists"
                            value="{{ request('search') }}"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status" id="status"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                            <option value="">All Artists</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-8 py-2 bg-black text-white text-sm hover:bg-gray-800 transition-smooth">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Artists Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach ($artists as $artist)
                <a href="{{ route('artists.show', $artist) }}" class="group">
                    <div class="space-y-4">
                        <div class="aspect-w-4 aspect-h-5 overflow-hidden">
                            <img src="{{ asset('storage/' . $artist->profile_image) }}" alt="{{ $artist->name }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-light">{{ $artist->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $artist->biography }}</p>
                            @if (!$artist->is_active)
                                <p class="text-xs text-gray-500">Archive</p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $artists->links() }}
        </div>
    </div>
</x-layouts.app>
