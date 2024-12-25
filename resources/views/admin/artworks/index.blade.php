<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Artworks</h1>

        <a href="{{ route('admin.artworks.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6 inline-block">Add New Artwork</a>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Artist</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($artworks as $artwork)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $artwork->title }}</td>
                            <td class="px-4 py-2">{{ $artwork->artist->name }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.artworks.edit', $artwork->id) }}"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</a>
                                <form action="{{ route('admin.artworks.destroy', $artwork->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
