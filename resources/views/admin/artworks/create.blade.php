<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Create New Artwork</h1>

        <form action="{{ route('admin.artworks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                @error('title')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Artist -->
            <div class="mb-4">
                <label for="artist_id" class="block text-sm font-medium text-gray-700">Artist</label>
                <select name="artist_id" id="artist_id"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Select Artist</option>
                    @foreach ($artists as $artist)
                        <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                            {{ $artist->name }}
                        </option>
                    @endforeach
                </select>
                @error('artist_id')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categories -->
            <div class="mb-4">
                <label for="categories" class="block text-sm font-medium text-gray-700">Categories</label>
                <select name="categories[]" id="categories" multiple
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('categories')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div class="mb-4">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <select name="tags[]" id="tags" multiple
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}"
                            {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                @error('tags')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    step="0.01" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dimensions -->
            <div class="mb-4">
                <label for="dimensions" class="block text-sm font-medium text-gray-700">Dimensions</label>
                <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('dimensions')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Medium -->
            <div class="mb-4">
                <label for="medium" class="block text-sm font-medium text-gray-700">Medium</label>
                <input type="text" id="medium" name="medium" value="{{ old('medium') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('medium')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Year Created -->
            <div class="mb-4">
                <label for="year_created" class="block text-sm font-medium text-gray-700">Year Created</label>
                <input type="number" id="year_created" name="year_created" value="{{ old('year_created') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('year_created')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Availability -->
            <div class="mb-4">
                <label for="is_available" class="block text-sm font-medium text-gray-700">Available</label>
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" id="is_available" value="1"
                    {{ old('is_available') ? 'checked' : '' }}
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('is_available')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured -->
            <div class="mb-4">
                <label for="is_featured" class="block text-sm font-medium text-gray-700">Featured</label>
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                    {{ old('is_featured') ? 'checked' : '' }}
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('is_featured')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock -->
            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                @error('stock')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Artwork Image</label>
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('image')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600">Create
                    Artwork</button>
            </div>
        </form>
    </div>
</x-layouts.admin>