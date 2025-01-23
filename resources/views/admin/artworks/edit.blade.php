<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Artwork</h2>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.artworks.update', $artwork->id) }}" method="POST" enctype="multipart/form-data"
                x-data="categoryForm">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <!-- Title -->
                    <x-form.input name="title" label="Title" value="{{ old('title', $artwork->title) }}" required />

                    <!-- Artist -->
                    <x-form.select name="artist_id" label="Artist" :options="$artists->pluck('name', 'id')" :selected="$artwork->artist_id" required />

                    <!-- Parent Category -->
                    <x-form.select name="parent_id" label="Parent Category" :options="$parentCategories->pluck('name', 'id')" :selected="old('parent_id', $artwork->categories->whereNull('parent_id')->first()?->id)"
                        x-model="parentCategoryId" x-on:change="fetchChildCategories" />

                    <!-- Child Category -->
                    <div x-show="childCategories.length > 0">
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Child
                                Category</label>
                            <select name="category_id" id="category_id"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <template x-for="category in childCategories" :key="category.id">
                                    <option :value="category.id" x-text="category.name"
                                        :selected="category.id ===
                                            {{ $artwork->categories->whereNotNull('parent_id')->first()?->id ?? 'null' }}">
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Tags -->
                    <x-form.select name="tags" label="Tags" :options="$tags->pluck('name', 'id')" :selected="$artwork->tags->pluck('id')->toArray()" multiple />

                    <!-- Description -->
                    <x-form.textarea name="description" label="Description"
                        value="{{ old('description', $artwork->description) }}" />

                    <!-- Price -->
                    <x-form.input type="number" name="price" label="Price"
                        value="{{ old('price', $artwork->price) }}" step="0.01" required />

                    <!-- Dimensions -->
                    <x-form.input name="dimensions" label="Dimensions"
                        value="{{ old('dimensions', $artwork->dimensions) }}" />

                    <!-- Medium -->
                    <x-form.input name="medium" label="Medium" value="{{ old('medium', $artwork->medium) }}" />

                    <!-- Year Created -->
                    <x-form.input type="number" name="year_created" label="Year Created"
                        value="{{ old('year_created', $artwork->year_created) }}" />

                    <!-- Availability -->
                    <x-form.checkbox name="is_available" label="Available" :value="$artwork->is_available" />

                    <!-- Featured -->
                    <x-form.checkbox name="is_featured" label="Featured" :value="$artwork->is_featured" />

                    <!-- Stock -->
                    <x-form.input type="number" name="stock" label="Stock"
                        value="{{ old('stock', $artwork->stock) }}" required />

                    <!-- Image -->
                    <x-form.image name="image" label="Artwork Image" :currentImage="$artwork->image" />
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md transition duration-200">
                        Update Artwork
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('categoryForm', () => ({
                parentCategoryId: {{ $artwork->categories->whereNull('parent_id')->first()?->id ?? ($parentCategories->first()?->id ?? 'null') }},
                childCategories: [],
                isLoading: true,

                init() {
                    console.log('Initializing with parentCategoryId:', this.parentCategoryId);

                    if (this.parentCategoryId) {
                        this.fetchChildCategories();
                    }
                    this.isLoading = false;
                },

                async fetchChildCategories() {
                    if (!this.parentCategoryId) {
                        this.childCategories = [];
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/admin/api/categories/${this.parentCategoryId}/children`);
                        const data = await response.json();
                        console.log('Fetched child categories:', data);

                        const selectedChildId =
                            {{ $artwork->categories->whereNotNull('parent_id')->first()?->id ?? 'null' }};
                        console.log('Selected child ID:', selectedChildId);

                        this.childCategories = data.map(category => ({
                            id: category.id,
                            name: category.name,
                            selected: category.id === selectedChildId
                        }));

                        console.log('Processed child categories:', this.childCategories);
                    } catch (error) {
                        console.error('Error fetching child categories:', error);
                        this.childCategories = [];
                    }
                }
            }));
        });
    </script>
</x-layouts.admin>
