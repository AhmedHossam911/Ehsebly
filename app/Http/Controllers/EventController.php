<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = \App\Models\Event::where('creator_id', auth()->id())
            ->orWhereHas('participants', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->withCount('participants')
            ->latest()
            ->get();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
        ]);

        $event = \App\Models\Event::create([
            'name' => $validated['name'],
            'date' => $validated['date'] ?? null,
            'creator_id' => auth()->id()
        ]);

        // Add creator as first participant
        $event->participants()->create([
            'user_id' => auth()->id()
        ]);

        return redirect()->route('events.show', $event)->with('status', 'Event created successfully!');
    }

    public function show(string $id)
    {
        $event = \App\Models\Event::with(['participants.user', 'creator', 'expenses.payers.participant.user'])->findOrFail($id);

        $isParticipant = $event->participants->contains('user_id', auth()->id());
        if (!$isParticipant && $event->creator_id !== auth()->id()) {
            abort(403, 'You are not a participant of this event.');
        }

        return view('events.show', compact('event'));
    }

    public function edit(string $id)
    {
        $event = \App\Models\Event::findOrFail($id);
        if ($event->creator_id !== auth()->id())
            abort(403);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
        $event = \App\Models\Event::findOrFail($id);
        if ($event->creator_id !== auth()->id())
            abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('status', 'Event updated.');
    }

    public function destroy(string $id)
    {
        $event = \App\Models\Event::findOrFail($id);
        if ($event->creator_id !== auth()->id())
            abort(403);

        $event->delete();

        return redirect()->route('events.index')->with('status', 'Event deleted.');
    }
}
