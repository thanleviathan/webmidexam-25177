<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Public list with search + filters.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q'));
        $when = $request->input('when');

        $query = Event::with('category')->withCount('registrations');

        if ($search !== '') {
            $query->where(function ($w) use ($search) {
                $w->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($when === 'upcoming') {
            $query->where('starts_at', '>=', now())->orderBy('starts_at');
        } elseif ($when === 'past') {
            $query->where('starts_at', '<', now())->orderByDesc('starts_at');
        } else {
            $query->orderByDesc('starts_at');
        }

        $events = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('events.index', compact('events', 'categories', 'search', 'when'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('events.create', ['event' => new Event(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $event = Event::create($data);

        return redirect()->route('events.show', $event)->with('success', 'Event created.');
    }

    public function show(Event $event)
    {
        $event->load(['category', 'user', 'comments.user'])->loadCount('registrations');

        $registration = null;
        if (auth()->check()) {
            $registration = $event->registrations()->where('user_id', auth()->id())->first();
        }

        return view('events.show', compact('event', 'registration'));
    }

    public function edit(Event $event)
    {
        $this->authorizeOwner($event);

        $categories = Category::orderBy('name')->get();

        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwner($event);

        $data = $this->validated($request);

        $taken = $event->registrations()->count();
        if ($data['capacity'] < $taken) {
            return back()->withInput()->withErrors([
                'capacity' => "Capacity cannot be lower than the {$taken} people already registered.",
            ]);
        }

        if ($request->hasFile('poster')) {
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('events.show', $event)->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOwner($event);

        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:150'],
            'starts_at' => ['required', 'date'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100000'],
            'category_id' => ['required', 'exists:categories,id'],
            'poster' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function authorizeOwner(Event $event): void
    {
        abort_unless($event->user_id === auth()->id(), 403, 'You can only change your own events.');
    }
}
