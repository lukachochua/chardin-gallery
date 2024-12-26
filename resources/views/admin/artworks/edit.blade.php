<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Edit Artwork</h1>

        <form action="{{ route('admin.artworks.update', $artwork->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <x-form.input name="title" label="Title" value="{{ old('title', $artwork->title) }}" required />

            <!-- Artist -->
            <x-form.select name="artist_id" label="Artist" :options="$artists->pluck('name', 'id')" :selected="$artwork->artist_id" required />


            <!-- Categories -->
            <x-form.select name="categories" label="Categories" :options="$categories->pluck('name', 'id')" :selected="$artwork->categories->pluck('id')->toArray()" multiple />

            <!-- Tags -->
            <x-form.select name="tags" label="Tags" :options="$tags->pluck('name', 'id')" :selected="$artwork->tags->pluck('id')->toArray()" multiple />

            <!-- Description -->
            <x-form.input name="description" label="Description" value="{{ old('description', $artwork->description) }}"
                type="textarea" />

            <!-- Price -->
            <x-form.input name="price" label="Price" value="{{ old('price', $artwork->price) }}" type="number"
                step="0.01" required />

            <!-- Dimensions -->
            <x-form.input name="dimensions" label="Dimensions" value="{{ old('dimensions', $artwork->dimensions) }}" />

            <!-- Medium -->
            <x-form.input name="medium" label="Medium" value="{{ old('medium', $artwork->medium) }}" />

            <!-- Year Created -->
            <x-form.input name="year_created" label="Year Created"
                value="{{ old('year_created', $artwork->year_created) }}" type="number" />

            <!-- Availability -->
            <x-form.input name="is_available" label="Available"
                value="{{ old('is_available', $artwork->is_available) }}" type="checkbox" />

            <!-- Featured -->
            <x-form.input name="is_featured" label="Featured" value="{{ old('is_featured', $artwork->is_featured) }}"
                type="checkbox" />

            <!-- Stock -->
            <x-form.input name="stock" label="Stock" value="{{ old('stock', $artwork->stock) }}" type="number"
                required />

            <!-- Image -->
            <x-form.image id="image" name="image" label="Artwork Image" :currentImage="$artwork->image" />

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600">Update
                    Artwork</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
