<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Category</h2>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Category Name -->
                <div class="mb-6">
                    <x-form.input name="name" label="Category Name" :value="old('name', $category->name)" required />
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <x-form.textarea name="description" label="Description" :value="old('description', $category->description)" />
                </div>

                <!-- Parent Category -->
                <div class="mb-6">
                    <x-form.select name="parent_id" label="Parent Category" :options="$categories" :selected="old('parent_id', $category->parent_id)" />
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm rounded-md transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
