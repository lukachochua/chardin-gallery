<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Add New Artist</h1>

        <form action="{{ route('admin.artists.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-form.input name="name" label="Name*" required />
            <x-form.textarea name="biography" label="Biography" />
            <x-form.input type="email" name="email" label="Email" />
            <x-form.input name="phone" label="Phone" />
            <x-form.input type="url" name="website" label="Website" />
            <x-form.input type="file" name="profile_image" label="Profile Image" />
            <x-form.checkbox name="is_active" label="Active" checked />

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Save</button>
        </form>
    </div>
</x-layouts.admin>
