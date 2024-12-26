<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Add New Artist</h1>

        <form action="{{ route('admin.artists.store') }}" method="POST">
            @csrf
            <x-form.input name="name" label="Name" required />
            <x-form.textarea name="biography" label="Biography" />
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Save</button>
        </form>
    </div>
</x-layouts.admin>
