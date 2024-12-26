<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Edit Artist</h1>

        <form action="{{ route('admin.artists.update', $artist->id) }}" method="POST">
            @csrf
            @method('PUT')
            <x-form.input name="name" label="Name" value="{{ $artist->name }}" required />
            <x-form.textarea name="biography" label="Biography" value="{{ $artist->biography }}" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
        </form>
    </div>
</x-layouts.admin