<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * "My Registrations" page.
     */
    public function index(Request $request)
    {
        $registrations = Registration::with('event.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('registrations.index', compact('registrations'));
    }

    /**
     * Register the logged-in user for an event.
     */
    public function store(Request $request, Event $event)
    {
        $user = $request->user();

        if ($event->starts_at->isPast()) {
            return back()->with('error', 'This event has already started, so registration is closed.');
        }

        if ($event->registrations()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You are already registered for this event.');
        }

        if ($event->seatsLeft() <= 0) {
            return back()->with('error', 'Sorry, this event is full.');
        }

        Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        return back()->with('success', 'You are registered. See you there!');
    }

    /**
     * Cancel a registration (only your own).
     */
    public function destroy(Request $request, Registration $registration)
    {
        abort_unless($registration->user_id === $request->user()->id, 403);

        $registration->delete();

        return back()->with('success', 'Your registration was cancelled.');
    }
}
