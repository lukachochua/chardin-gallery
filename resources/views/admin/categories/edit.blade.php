<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Edit Category</h1>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-form.input name="name" label="Category Name" :value="old('name', $category->name)" required />
            <x-form.textarea name="description" label="Description" :value="old('description', $category->description)" />
            <x-form.select name="parent_id" label="Parent Category" :options="$categories" :selected="old('parent_id', $category->parent_id)" />
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Update Category
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
