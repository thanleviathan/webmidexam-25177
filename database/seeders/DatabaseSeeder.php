<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::firstOrCreate(
            ['email' => 'demo@cyanic.test'],
            ['name' => 'Demo User', 'password' => Hash::make('password1')]
        );

        $guest = User::firstOrCreate(
            ['email' => 'guest@cyanic.test'],
            ['name' => 'Guest User', 'password' => Hash::make('password2')]
        );

        $names = [
            'Workshop' => 'Hands-on sessions to learn a new skill.',
            'Seminar' => 'Talks and presentations by speakers.',
            'Competition' => 'Contests and hackathons.',
            'Social' => 'Gatherings, meetups and community fun.',
        ];

        $cats = [];
        foreach ($names as $name => $description) {
            $cats[$name] = Category::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        if (Event::count() > 0) {
            return;
        }

        $events = [
           ['Event 1', 'Workshop', 'Description for event 1.', 'Location 1', '+3 days', 30],
           ['Event 2', 'Seminar', 'Description for event 2.', 'Location 2', '+7 days', 120],
           ['Event 3', 'Competition', 'Description for event 3.', 'Location 3', '+14 days', 60],
           ['Event 4', 'Social', 'Description for event 4.', 'Location 4', '+5 days', 40],
           ['Event 5', 'Workshop', 'Description for event 5.', 'Location 5', '+10 days', 25],
           ['Event 6', 'Seminar', 'Description for event 6.', 'Location 6', '-5 days', 50],
        ];

        foreach ($events as $i => [$title, $cat, $desc, $loc, $when, $cap]) {
            $event = Event::create([
                'user_id' => $i % 2 === 0 ? $demo->id : $guest->id,
                'category_id' => $cats[$cat]->id,
                'title' => $title,
                'description' => $desc,
                'location' => $loc,
                'starts_at' => now()->modify($when)->setTime(9 + $i, 0),
                'capacity' => $cap,
            ]);

            Registration::create(['user_id' => $demo->id, 'event_id' => $event->id]);
            if ($i % 2 === 1) {
                Registration::firstOrCreate(['user_id' => $guest->id, 'event_id' => $event->id]);
            }

            Comment::create([
                'user_id' => $guest->id,
                'event_id' => $event->id,
                'body' => 'Looking forward to this one!',
            ]);
        }
    }
}
