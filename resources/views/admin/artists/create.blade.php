<x-layouts.admin>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 flex justify-between items-center bg-gray-100 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Add New Artist</h2>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.artists.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <x-form.input name="name" label="Name*" required />
                    <x-form.textarea name="biography" label="Biography" />
                    <x-form.input type="email" name="email" label="Email" />
                    <x-form.input name="phone" label="Phone" />
                    <x-form.input type="url" name="website" label="Website" />
                    <x-form.input type="file" name="profile_image" label="Profile Image" />
                    <x-form.checkbox name="is_active" label="Active" checked />
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
