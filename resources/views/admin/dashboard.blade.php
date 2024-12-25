<x-layouts.admin>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h5 class="text-xl font-semibold">Artists</h5>
                <p class="text-3xl">{{ $artistsCount }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h5 class="text-xl font-semibold">Artworks</h5>
                <p class="text-3xl">{{ $artworksCount }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h5 class="text-xl font-semibold">Categories</h5>
                <p class="text-3xl">{{ $categoriesCount }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h5 class="text-xl font-semibold">Tags</h5>
                <p class="text-3xl">{{ $tagsCount }}</p>
            </div>
        </div>
    </div>
</x-layouts.admin>
