<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Exhibition Image -->
            <div class="aspect-w-4 aspect-h-3">
                <img src="{{ asset('storage/' . $exhibition->image) }}" alt="{{ $exhibition->title }}"
                    class="w-full h-full object-cover">
            </div>

            <!-- Exhibition Details -->
            <div class="space-y-8">
                <div class="space-y-2">
                    <div class="flex justify-between items-start">
                        <h1 class="text-3xl font-light">{{ $exhibition->title }}</h1>
                        <span @class([
                            'px-3 py-1 text-sm rounded',
                            'bg-blue-100 text-blue-800' => $exhibition->status === 'upcoming',
                            'bg-emerald-100 text-emerald-800' => $exhibition->status === 'running',
                            'bg-gray-100 text-gray-800' => $exhibition->status === 'done',
                        ])>
                            {{ match ($exhibition->status) {
                                'upcoming' => 'Opening Soon',
                                'running' => 'Now On View',
                                'done' => 'Past Exhibition',
                                default => $exhibition->status,
                            } }}
                        </span>
                    </div>
                    <p class="text-lg">{{ $exhibition->location }}</p>
                    <p class="text-sm text-gray-600">
                        @if ($exhibition->status === 'upcoming')
                            Opening {{ $exhibition->start_date->format('M d, Y') }}
                        @elseif($exhibition->status === 'running')
                            On view through {{ $exhibition->end_date->format('M d, Y') }}
                        @else
                            {{ $exhibition->start_date->format('M d, Y') }} -
                            {{ $exhibition->end_date->format('M d, Y') }}
                        @endif
                    </p>
                </div>

                <div class="space-y-4 text-sm">
                    <p class="leading-relaxed">{{ $exhibition->description }}</p>

                    <!-- Featured Artists -->
                    <div class="pt-4 border-t border-gray-100">
                        <h2 class="text-gray-600 mb-2">Featured Artists</h2>
                        <div class="space-y-1">
                            @foreach ($exhibition->artists as $artist)
                                <a href="{{ route('artists.show', $artist) }}"
                                    class="block text-black hover:underline">{{ $artist->name }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Featured Artworks -->
                    @if ($exhibition->artworks->count() > 0)
                        <div class="pt-4 border-t border-gray-100">
                            <h2 class="text-gray-600 mb-4">Featured Artworks</h2>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($exhibition->artworks as $artwork)
                                    <a href="{{ route('artworks.show', $artwork) }}"
                                        class="hover:opacity-75 transition-smooth">
                                        <img src="{{ asset('storage/' . $artwork->image) }}"
                                            alt="{{ $artwork->title }}" class="w-full h-32 object-cover">
                                        <p class="mt-1 text-xs">{{ $artwork->title }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Exhibitions -->
        @if ($relatedExhibitions->count() > 0)
            <div class="mt-24">
                <h2 class="text-2xl font-light mb-8">Related Exhibitions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    @foreach ($relatedExhibitions as $related)
                        <a href="{{ route('exhibitions.show', $related) }}" class="group">
                            <div class="space-y-4">
                                <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-light">{{ $related->title }}</h3>
                                        <span @class([
                                            'px-2 py-1 text-xs rounded',
                                            'bg-blue-100 text-blue-800' => $related->status === 'upcoming',
                                            'bg-emerald-100 text-emerald-800' => $related->status === 'running',
                                            'bg-gray-100 text-gray-800' => $related->status === 'done',
                                        ])>
                                            {{ match ($related->status) {
                                                'upcoming' => 'Upcoming',
                                                'running' => 'Now Open',
                                                'done' => 'Past',
                                                default => $related->status,
                                            } }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $related->location }}</p>
                                    <p class="text-sm">{{ $related->start_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
