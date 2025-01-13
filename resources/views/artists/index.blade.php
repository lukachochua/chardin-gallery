<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Our Artists</h1>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form action="{{ route('artists.index') }}" method="GET" 
                  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Name Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search by Name</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>

                <!-- Active Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Artists Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($artists as $artist)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('artists.show', $artist) }}">
                        <img src="{{ asset('storage/' . $artist->profile_image) }}" 
                             alt="{{ $artist->name }}"
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $artist->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $artist->biography }}</p>
                            <div class="mt-2 flex items-center">
                                <span class="px-2 py-1 text-xs {{ $artist->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded">
                                    {{ $artist->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $artists->links() }}
        </div>
    </div>
</x-layouts.app>