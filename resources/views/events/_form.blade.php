<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
        <select id="category_id" name="category_id" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $event->category_id) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        @if ($categories->isEmpty())
            <p class="mt-1 text-sm text-gray-500">
                No categories yet. <a class="text-cyan-700 underline" href="{{ route('categories.create') }}">Create one first.</a>
            </p>
        @endif
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description" name="description" rows="5" required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('description', $event->description) }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="starts_at" class="block text-sm font-medium text-gray-700">Date and time</label>
            <input id="starts_at" name="starts_at" type="datetime-local"
                   value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            @error('starts_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (seats)</label>
            <input id="capacity" name="capacity" type="number" min="1" value="{{ old('capacity', $event->capacity ?? 50) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            @error('capacity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="poster" class="block text-sm font-medium text-gray-700">Poster image (optional, max 2 MB)</label>
            <input id="poster" name="poster" type="file" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:rounded-md file:border-0 file:bg-cyan-50 file:px-3 file:py-2 file:text-cyan-700">
            @error('poster') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            @if ($event->poster)
                <img src="{{ asset('storage/'.$event->poster) }}" alt="Current poster" class="mt-2 h-24 rounded-md object-cover">
            @endif
        </div>
    </div>

    <div class="flex items-center space-x-3">
        <button type="submit" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-700">Save event</button>
        <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
    </div>
</form>
