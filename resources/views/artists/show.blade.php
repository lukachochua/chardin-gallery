<x-layouts.app>
    <article class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <!-- Artist Header -->
        <header class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-24">
            <div class="aspect-w-4 aspect-h-5">
                <img src="{{ asset('storage/' . $artist->profile_image) }}" alt="{{ $artist->name }}"
                    class="w-full h-full object-cover">
            </div>

            <div class="space-y-8">
                <div class="space-y-2">
                    <h1 class="text-3xl font-light">{{ $artist->name }}</h1>
                    @if (!$artist->is_active)
                        <p class="text-sm text-gray-500">Archive</p>
                    @endif
                </div>

                <div class="space-y-4 text-sm">
                    <div class="prose prose-sm max-w-none">
                        <p class="leading-relaxed">{{ $artist->biography }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 pt-8 border-t border-gray-100">
                        @if ($artist->email)
                            <div>
                                <p class="text-gray-600">Email</p>
                                <p>{{ $artist->email }}</p>
                            </div>
                        @endif

                        @if ($artist->phone)
                            <div>
                                <p class="text-gray-600">Phone</p>
                                <p>{{ $artist->phone }}</p>
                            </div>
                        @endif

                        @if ($artist->website)
                            <div>
                                <p class="text-gray-600">Website</p>
                                <a href="{{ $artist->website }}" class="hover:text-gray-600 transition-smooth">
                                    {{ $artist->website }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Artist's Portfolio -->
        @if ($artist->artworks->count() > 0)
            <section class="mt-24">
                <h2 class="text-2xl font-light mb-12">Selected Works</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    @foreach ($artist->artworks as $artwork)
                        <a href="{{ route('artworks.show', $artwork) }}" class="group">
                            <div class="space-y-4">
                                <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                                    <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-light">{{ $artwork->title }}</h3>
                                    <p class="text-sm">{{ $artwork->year_created }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
</x-layouts.app>
