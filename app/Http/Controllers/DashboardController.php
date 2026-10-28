<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $uid = $request->user()->id;

        $stats = [
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('starts_at', '>=', now())->count(),
            'my_events' => Event::where('user_id', $uid)->count(),
            'my_registrations' => Registration::where('user_id', $uid)->count(),
            'attendees_on_my_events' => Registration::whereIn('event_id', Event::where('user_id', $uid)->select('id'))->count(),
            'total_users' => User::count(),
        ];

        $perCategory = Category::withCount('events')->orderByDesc('events_count')->get();

        $myUpcoming = Event::whereHas('registrations', function ($q) use ($uid) {
                $q->where('user_id', $uid);
            })
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'perCategory', 'myUpcoming'));
    }
}
