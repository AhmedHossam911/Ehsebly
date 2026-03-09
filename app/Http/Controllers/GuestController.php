<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function showJoinForm($token)
    {
        $event = \App\Models\Event::where('guest_token', $token)->firstOrFail();

        // If logged in, automatically join them and redirect to main event view
        if (auth()->check()) {
            $event->participants()->firstOrCreate([
                'user_id' => auth()->id()
            ]);
            return redirect()->route('events.show', $event)->with('status', 'Joined event automatically.');
        }

        // Check if guest is already joined in this session
        if (session()->has('guest_participant_' . $event->id)) {
            return redirect()->route('guest.dashboard', $token);
        }

        return view('guest.join', compact('event'));
    }

    public function join(Request $request, $token)
    {
        $event = \App\Models\Event::where('guest_token', $token)->firstOrFail();

        $request->validate([
            'guest_name' => 'required|string|max:255'
        ]);

        $participant = $event->participants()->create([
            'guest_name' => $request->guest_name
        ]);

        session(['guest_participant_' . $event->id => $participant->id]);

        return redirect()->route('guest.dashboard', $token)->with('status', 'Joined as guest successfully.');
    }

    public function dashboard($token)
    {
        $event = \App\Models\Event::with(['participants.user', 'creator', 'expenses.payers.participant.user'])->where('guest_token', $token)->firstOrFail();

        if (!session()->has('guest_participant_' . $event->id) && !auth()->check()) {
            return redirect()->route('guest.join.form', $token);
        }

        return view('guest.dashboard', compact('event'));
    }
}
