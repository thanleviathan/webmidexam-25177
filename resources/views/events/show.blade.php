<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h2>
            <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:underline">← All events</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                @if ($event->poster)
                    <img src="{{ asset('storage/'.$event->poster) }}" alt="{{ $event->title }} poster" class="h-64 w-full object-cover">
                @endif

                <div class="space-y-4 p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-cyan-50 px-2 py-0.5 text-xs font-medium text-cyan-700">{{ $event->category->name }}</span>
                        <span class="text-sm text-gray-500">Organised by {{ $event->user->name }}</span>
                    </div>

                    <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                        <div>
                            <dt class="text-gray-500">When</dt>
                            <dd class="font-medium text-gray-900">{{ $event->starts_at->format('D, d M Y · H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Where</dt>
                            <dd class="font-medium text-gray-900">{{ $event->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Seats</dt>
                            <dd class="font-medium text-gray-900">
                                {{ $event->registrations_count }} / {{ $event->capacity }} taken
                            </dd>
                        </div>
                    </dl>

                    <p class="whitespace-pre-line text-gray-700">{{ $event->description }}</p>

                    <div class="flex flex-wrap items-center gap-3 border-t pt-4">
                        {{-- Register / cancel --}}
                        @guest
                            <a href="{{ route('login') }}" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-700">
                                Log in to register
                            </a>
                        @else
                            @if ($registration)
                                <form method="POST" action="{{ route('registrations.destroy', $registration) }}"
                                      onsubmit="return confirm('Cancel your registration?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                                        Cancel registration
                                    </button>
                                </form>
                                <span class="text-sm text-green-700">You are registered.</span>
                            @elseif ($event->starts_at->isPast())
                                <span class="text-sm text-gray-500">Registration closed (event already started).</span>
                            @elseif ($event->seatsLeft() <= 0)
                                <span class="text-sm text-red-600">This event is full.</span>
                            @else
                                <form method="POST" action="{{ route('registrations.store', $event) }}">
                                    @csrf
                                    <button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-700">
                                        Register
                                    </button>
                                </form>
                            @endif
                        @endguest

                        {{-- Owner actions --}}
                        @auth
                            @if ($event->user_id === auth()->id())
                                <a href="{{ route('events.edit', $event) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                <form method="POST" action="{{ route('events.destroy', $event) }}"
                                      onsubmit="return confirm('Delete this event? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Delete</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Comments --}}
            <div class="space-y-4 bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900">Comments ({{ $event->comments->count() }})</h3>

                @auth
                    <form method="POST" action="{{ route('comments.store', $event) }}" class="space-y-2">
                        @csrf
                        <textarea name="body" rows="2" required placeholder="Write a comment..."
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('body') }}</textarea>
                        @error('body') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        <button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-700">Post comment</button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">
                        <a class="text-cyan-700 underline" href="{{ route('login') }}">Log in</a> to leave a comment.
                    </p>
                @endauth

                <div class="divide-y divide-gray-100">
                    @forelse ($event->comments->sortByDesc('created_at') as $comment)
                        <div class="flex items-start justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $comment->user->name }}
                                    <span class="ml-1 text-xs font-normal text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </p>
                                <p class="whitespace-pre-line text-sm text-gray-700">{{ $comment->body }}</p>
                            </div>
                            @auth
                                @if ($comment->user_id === auth()->id())
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                          onsubmit="return confirm('Delete this comment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    @empty
                        <p class="py-3 text-sm text-gray-500">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
