<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Create New Artwork</h1>

        <form action="{{ route('admin.artworks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <x-form.input name="title" label="Title" :value="old('title')" required />

            <!-- Artist -->
            <x-form.select name="artist_id" label="Artist" :options="$artists->pluck('name', 'id')" :selected="old('artist_id')" required />

            <!-- Categories -->
            <x-form.select name="categories[]" label="Categories" :options="$categories->pluck('name', 'id')" :selected="old('categories', [])" multiple />

            <!-- Tags -->
            <x-form.select name="tags[]" label="Tags" :options="$tags->pluck('name', 'id')" :selected="old('tags', [])" multiple />

            <!-- Description -->
            <x-form.textarea name="description" label="Description" :value="old('description')" />

            <!-- Price -->
            <x-form.input type="number" name="price" label="Price" :value="old('price')" step="0.01" required />

            <!-- Dimensions -->
            <x-form.input name="dimensions" label="Dimensions" :value="old('dimensions')" />

            <!-- Medium -->
            <x-form.input name="medium" label="Medium" :value="old('medium')" />

            <!-- Year Created -->
            <x-form.input type="number" name="year_created" label="Year Created" :value="old('year_created')" />

            <!-- Availability -->
            <x-form.checkbox name="is_available" label="Available" :checked="old('is_available')" />

            <!-- Featured -->
            <x-form.checkbox name="is_featured" label="Featured" :checked="old('is_featured')" />

            <!-- Stock -->
            <x-form.input type="number" name="stock" label="Stock" :value="old('stock')" required />

            <!-- Image -->
            <x-form.image name="image" label="Artwork Image" />

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600">Create
                    Artwork</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
