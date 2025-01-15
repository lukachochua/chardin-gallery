<x-layouts.admin>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Create New Exhibition</h2>

            <form action="{{ route('admin.exhibitions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">
                        Title
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">
                            Start Date
                        </label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('start_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">
                            End Date
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('end_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-4">
                    <label for="location" class="block text-gray-700 text-sm font-bold mb-2">
                        Location
                    </label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('location')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">
                        Exhibition Image
                    </label>
                    <input type="file" name="image" id="image"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('image')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Artists Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Artists
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-60 overflow-y-auto border rounded p-3">
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
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Artworks Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Artworks
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-60 overflow-y-auto border rounded p-3">
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
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.exhibitions.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Exhibition
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
