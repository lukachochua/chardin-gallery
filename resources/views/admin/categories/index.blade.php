<x-layouts.admin>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            {{-- Header Section with Primary Action --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Categories</h2>
                <a href="{{ route('admin.categories.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Category
                </a>
            </div>

            {{-- Maintenance Actions Section --}}
            <div class="mb-6 pb-4 border-b border-gray-200">
                <form action="{{ route('admin.categories.rebuild-tree') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded border border-gray-300 flex items-center"
                        onclick="return confirm('Are you sure you want to rebuild the category tree? This will reorganize all categories based on their parent-child relationships.')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Rebuild Category Tree
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Level
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Parent
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($categories as $category)
                            <tr class="{{ $category->depth > 0 ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-gray-400 mr-2">
                                            {!! str_repeat('⤷', $category->depth) !!}
                                        </span>
                                        {{ $category->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($category->isRoot())
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Root
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Level {{ $category->depth }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $category->parent ? $category->parent->name : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure? This will also delete all subcategories.')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
