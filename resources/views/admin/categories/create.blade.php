<x-layouts.admin>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Create New Category</h2>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <!-- Category Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                        Name
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Category Selection -->
                <div class="mb-4">
                    <label for="parent_id" class="block text-gray-700 text-sm font-bold mb-2">
                        Parent Category (optional)
                    </label>
                    <select name="parent_id" 
                            id="parent_id"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">No Parent (Root Category)</option>
                        @foreach($rootCategories as $rootCategory)
                            <option value="{{ $rootCategory->id }}" {{ old('parent_id') == $rootCategory->id ? 'selected' : '' }}>
                                {{ $rootCategory->name }}
                            </option>
                            @foreach($rootCategory->children as $childCategory)
                                <option value="{{ $childCategory->id }}" {{ old('parent_id') == $childCategory->id ? 'selected' : '' }}>
                                    -- {{ $childCategory->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                        Description (optional)
                    </label>
                    <textarea name="description" 
                              id="description"
                              rows="3"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
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