<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Events</h2>
            @auth
                <a href="{{ route('events.create') }}" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-700">
                    + New Event
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />

            {{-- Search and filters --}}
            <form method="GET" action="{{ route('events.index') }}"
                  class="grid grid-cols-1 gap-3 rounded-lg bg-white p-4 shadow sm:grid-cols-5">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search title, place or description"
                       class="rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 sm:col-span-2">

                <select name="category" class="rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="when" class="rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Any time</option>
                    <option value="upcoming" @selected($when === 'upcoming')>Upcoming</option>
                    <option value="past" @selected($when === 'past')>Past</option>
                </select>

                <div class="flex items-center space-x-2">
                    <button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-700">Filter</button>
                    <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:underline">Reset</a>
                </div>
            </form>

            {{-- Event cards --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <a href="{{ route('events.show', $event) }}" class="block overflow-hidden rounded-lg bg-white shadow transition hover:shadow-md">
                        @if ($event->poster)
                            <img src="{{ asset('storage/'.$event->poster) }}" alt="{{ $event->title }} poster" class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-40 w-full items-center justify-center bg-gradient-to-br from-cyan-400 to-cyan-600 text-4xl font-bold text-white">
                                {{ strtoupper(substr($event->title, 0, 1)) }}
                            </div>
                        @endif

                        <div class="space-y-2 p-4">
                            <span class="inline-block rounded-full bg-cyan-50 px-2 py-0.5 text-xs font-medium text-cyan-700">
                                {{ $event->category->name }}
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $event->starts_at->format('D, d M Y · H:i') }}</p>
                            <p class="text-sm text-gray-600">{{ $event->location }}</p>
                            <p class="text-sm {{ $event->seatsLeft() > 0 ? 'text-gray-500' : 'text-red-600' }}">
                                {{ $event->seatsLeft() > 0 ? $event->seatsLeft().' seats left' : 'Full' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full rounded-lg bg-white p-8 text-center text-gray-500 shadow">
                        No events found. Try a different search.
                    </p>
                @endforelse
            </div>

            <div>{{ $events->links() }}</div>
        </div>
    </div>
</x-app-layout>
