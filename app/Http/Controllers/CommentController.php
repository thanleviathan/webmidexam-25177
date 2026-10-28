<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        Comment::create([
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
            'body' => $data['body'],
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Comment posted.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $eventId = $comment->event_id;
        $comment->delete();

        return redirect()->route('events.show', $eventId)->with('success', 'Comment deleted.');
    }
}
