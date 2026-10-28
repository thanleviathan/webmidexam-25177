<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">My Registrations</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-cyan-50 text-left text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Event</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">When</th>
                            <th class="px-4 py-3">Registered</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($registrations as $registration)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    <a class="text-cyan-700 hover:underline" href="{{ route('events.show', $registration->event) }}">
                                        {{ $registration->event->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $registration->event->category->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $registration->event->starts_at->format('d M Y · H:i') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $registration->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('registrations.destroy', $registration) }}"
                                          onsubmit="return confirm('Cancel this registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    You have not registered for any event yet.
                                    <a class="text-cyan-700 underline" href="{{ route('events.index') }}">Browse events</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
