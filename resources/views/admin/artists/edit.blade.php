<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Edit Artist</h1>

        <form action="{{ route('admin.artists.update', $artist->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">Name</label>
                <input type="text" name="name" id="name" class="mt-1 p-2 border rounded-md w-full"
                    value="{{ $artist->name }}" required>
            </div>

            <div class="mb-4">
                <label for="biography" class="block text-sm font-medium">Biography</label>
                <textarea name="biography" id="biography" class="mt-1 p-2 border rounded-md w-full">{{ $artist->biography }}</textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
        </form>
    </div>
</x-layouts.admin>
