<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Create New Exhibition</h2>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.exhibitions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="datetime-local" name="start_date" id="start_date"
                                value="{{ old('start_date') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Exhibition Image</label>
                        <input type="file" name="image" id="image"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Artists Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Artists</label>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3 mt-1">
                            @foreach ($artists as $artist)
                                <div class="flex items-center">
                                    <input type="checkbox" name="artists[]" value="{{ $artist->id }}"
                                        id="artist-{{ $artist->id }}" class="rounded border-gray-300"
                                        {{ in_array($artist->id, old('artists', [])) ? 'checked' : '' }}>
                                    <label for="artist-{{ $artist->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $artist->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('artists')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Artworks Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Artworks</label>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3 mt-1">
                            @foreach ($artworks as $artwork)
                                <div class="flex items-center">
                                    <input type="checkbox" name="artworks[]" value="{{ $artwork->id }}"
                                        id="artwork-{{ $artwork->id }}" class="rounded border-gray-300"
                                        {{ in_array($artwork->id, old('artworks', [])) ? 'checked' : '' }}>
                                    <label for="artwork-{{ $artwork->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $artwork->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('artworks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end mt-6">
                    <a href="{{ route('admin.exhibitions.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200 mr-2">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Create Exhibition
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
