<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Create New Category</h2>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category Selection -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category
                            (optional)</label>
                        <select name="parent_id" id="parent_id"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">No Parent (Root Category)</option>
                            @foreach ($rootCategories as $rootCategory)
                                <option value="{{ $rootCategory->id }}"
                                    {{ old('parent_id') == $rootCategory->id ? 'selected' : '' }}>
                                    {{ $rootCategory->name }}
                                </option>
                                @foreach ($rootCategory->children as $childCategory)
                                    <option value="{{ $childCategory->id }}"
                                        {{ old('parent_id') == $childCategory->id ? 'selected' : '' }}>
                                        -- {{ $childCategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description
                            (optional)</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition duration-200">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const parentSelect = document.getElementById('parent_id');
                const nameInput = document.getElementById('name');

                // Optional: Update display based on parent selection
                parentSelect.addEventListener('change', function() {
                    const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                    if (selectedOption.value) {
                        // If a parent is selected, we could add visual indicators or help text
                        nameInput.placeholder = `Sub-category under ${selectedOption.text}`;
                    } else {
                        nameInput.placeholder = 'Root category name';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.admin>
