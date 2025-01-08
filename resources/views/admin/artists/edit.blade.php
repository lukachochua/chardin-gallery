<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Edit Artist</h1>

        <form action="{{ route('admin.artists.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.input name="name" label="Name*" value="{{ old('name', $artist->name) }}" required />

            <x-form.textarea name="biography" label="Biography" :value="$artist->biography"></x-form.textarea>
            <x-form.input type="email" name="email" label="Email" value="{{ old('email', $artist->email) }}" />
            <x-form.input name="phone" label="Phone" value="{{ old('phone', $artist->phone) }}" />
            <x-form.input type="url" name="website" label="Website"
                value="{{ old('website', $artist->website) }}" />
            @if ($artist->profile_image)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Current Profile Image</label>
                    <img src="{{ asset('storage/' . $artist->profile_image) }}" alt="Profile Image"
                        class="w-20 h-20 object-cover mt-2 rounded rounded-lg">
                </div>
            @endif
            <x-form.input type="file" name="profile_image" label="Profile Image" />
            <x-form.checkbox name="is_active" label="Active" :value="old('is_active', $artist->is_active)" />

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
        </form>
    </div>
</x-layouts.admin>
