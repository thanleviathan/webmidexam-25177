<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Event</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @include('events._form', [
                    'action' => route('events.update', $event),
                    'method' => 'PUT',
                ])
            </div>
        </div>
    </div>
</x-app-layout>
