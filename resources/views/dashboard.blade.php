<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />

            <p class="text-gray-700">Welcome back, <span class="font-semibold">{{ Auth::user()->name }}</span>.</p>

            {{-- Statistic tiles --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
                @foreach ([
                    'Total events' => $stats['total_events'],
                    'Upcoming events' => $stats['upcoming_events'],
                    'Events I created' => $stats['my_events'],
                    'My registrations' => $stats['my_registrations'],
                    'Attendees on my events' => $stats['attendees_on_my_events'],
                    'Registered users' => $stats['total_users'],
                ] as $label => $value)
                    <div class="rounded-lg border-l-4 border-cyan-500 bg-white p-4 shadow">
                        <p class="text-sm text-gray-500">{{ $label }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Events per category --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Events per category</h3>
                    @php $max = max(1, $perCategory->max('events_count') ?? 1); @endphp
                    @forelse ($perCategory as $category)
                        <div class="mb-3">
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>{{ $category->name }}</span>
                                <span>{{ $category->events_count }}</span>
                            </div>
                            <div class="h-2 rounded bg-cyan-100">
                                <div class="h-2 rounded bg-cyan-500" style="width: {{ ($category->events_count / $max) * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No categories yet.</p>
                    @endforelse
                </div>

                {{-- My upcoming events --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">My upcoming events</h3>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($myUpcoming as $event)
                            <li class="py-2">
                                <a class="font-medium text-cyan-700 hover:underline" href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                                <p class="text-sm text-gray-500">{{ $event->starts_at->format('D, d M Y · H:i') }} · {{ $event->location }}</p>
                            </li>
                        @empty
                            <li class="py-2 text-sm text-gray-500">
                                You are not registered for any upcoming event.
                                <a class="text-cyan-700 underline" href="{{ route('events.index') }}">Find one</a>.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
