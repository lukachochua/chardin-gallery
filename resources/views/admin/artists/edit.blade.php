<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Artist</h2>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.artists.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <x-form.input name="name" label="Name*" value="{{ old('name', $artist->name) }}" required />
                    <x-form.textarea name="biography" label="Biography" :value="$artist->biography" />
                    <x-form.input type="email" name="email" label="Email"
                        value="{{ old('email', $artist->email) }}" />
                    <x-form.input name="phone" label="Phone" value="{{ old('phone', $artist->phone) }}" />
                    <x-form.input type="url" name="website" label="Website"
                        value="{{ old('website', $artist->website) }}" />

                    <!-- Current Profile Image -->
                    @if ($artist->profile_image)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Current Profile Image</label>
                            <img src="{{ asset('storage/' . $artist->profile_image) }}" alt="Profile Image"
                                class="w-20 h-20 object-cover mt-2 rounded-lg">
                        </div>
                    @endif

                    <x-form.input type="file" name="profile_image" label="Profile Image" />
                    <x-form.checkbox name="is_active" label="Active" :value="old('is_active', $artist->is_active)" />
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
