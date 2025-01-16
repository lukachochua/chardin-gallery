<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <!-- Filters -->
        <div class="mb-16">
            <form action="{{ route('exhibitions.index') }}" method="GET" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Status Filter -->
                    <div>
                        <select name="status" id="status"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                            <option value="">All Exhibitions</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>
                                Upcoming Exhibitions
                            </option>
                            <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>
                                Running Exhibitions
                            </option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>
                                Past Exhibitions
                            </option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <input type="text" name="search" id="search" placeholder="Search exhibitions"
                            value="{{ request('search') }}"
                            class="w-full bg-transparent border-b border-gray-200 py-2 text-sm focus:outline-none focus:border-black">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-8 py-2 bg-black text-white text-sm hover:bg-gray-800 transition-smooth">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Exhibitions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach ($exhibitions as $exhibition)
                <a href="{{ route('exhibitions.show', $exhibition) }}" class="group">
                    <div class="space-y-4">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img src="{{ asset('storage/' . $exhibition->image) }}" alt="{{ $exhibition->title }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-smooth">
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-light">{{ $exhibition->title }}</h3>
                                <span @class([
                                    'px-2 py-1 text-xs rounded',
                                    'bg-blue-100 text-blue-800' => $exhibition->status === 'upcoming',
                                    'bg-emerald-100 text-emerald-800' => $exhibition->status === 'running',
                                    'bg-gray-100 text-gray-800' => $exhibition->status === 'done',
                                ])>
                                    {{ match ($exhibition->status) {
                                        'upcoming' => 'Upcoming',
                                        'running' => 'Now Open',
                                        'done' => 'Past Exhibition',
                                        default => $exhibition->status,
                                    } }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $exhibition->location }}</p>
                            <p class="text-sm">{{ $exhibition->start_date->format('M d, Y') }} -
                                {{ $exhibition->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $exhibitions->links() }}
        </div>
    </div>
</x-layouts.app>
s
