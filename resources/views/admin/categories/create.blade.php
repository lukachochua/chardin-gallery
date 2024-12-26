<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Create New Category</h1>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <x-form.input name="name" label="Category Name" required />
            <x-form.textarea name="description" label="Description" />
            <x-form.select name="parent_id" label="Parent Category" :options="$categories->pluck('name', 'id')->toArray()" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Category</button>
        </form>
    </div>
</x-layouts.admin>
