<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Exhibitions</h2>
            <a href="{{ route('admin.exhibitions.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm rounded">
                Add New Exhibition
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Artists
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Artworks
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($exhibitions as $exhibition)
                        <tr>
                            <td class="px-6 py-4">{{ $exhibition->name }}</td>
                            <td class="px-6 py-4">{{ $exhibition->status }}</td>
                            <td class="px-6 py-4">
                                @forelse ($exhibition->artists as $artist)
                                    <span>{{ $artist->name }}</span>
                                @empty
                                    <span>No artists assigned</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4">
                                @forelse ($exhibition->artworks as $artwork)
                                    <span>{{ $artwork->title }}</span>
                                @empty
                                    <span>No artworks available</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.exhibitions.edit', $exhibition) }}" class="text-indigo-600">Edit</a>
                                <form action="{{ route('admin.exhibitions.destroy', $exhibition) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
