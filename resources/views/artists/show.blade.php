<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Artist Profile Image -->
            <div class="relative h-96">
                <img src="{{ asset('storage/' . $artist->profile_image) }}" alt="{{ $artist->name }}"
                    class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6">
                    <h1 class="text-4xl font-bold text-white">{{ $artist->name }}</h1>
                </div>
            </div>

            <!-- Artist Details -->
            <div class="p-6">
                <!-- Biography -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">Biography</h2>
                    <p class="text-gray-700">{{ $artist->biography }}</p>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    @if ($artist->email)
                        <div>
                            <p class="text-sm text-gray-600">Email:</p>
                            <p class="text-gray-800">{{ $artist->email }}</p>
                        </div>
                    @endif
                    @if ($artist->phone)
                        <div>
                            <p class="text-sm text-gray-600">Phone:</p>
                            <p class="text-gray-800">{{ $artist->phone }}</p>
                        </div>
                    @endif
                    @if ($artist->website)
                        <div>
                            <p class="text-sm text-gray-600">Website:</p>
                            <a href="{{ $artist->website }}" class="text-blue-600 hover:text-blue-800">
                                {{ $artist->website }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Status:</p>
                        <p class="text-gray-800">
                            <span
                                class="px-2 py-1 text-xs {{ $artist->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded">
                                {{ $artist->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Artist's Artworks -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Artist's Portfolio</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($artist->artworks as $artwork)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <a href="{{ route('artworks.show', $artwork) }}">
                                    <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}"
                                        class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $artwork->title }}</h3>
                                        <p class="text-lg font-bold text-gray-900">
                                            ${{ number_format($artwork->price, 2) }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
