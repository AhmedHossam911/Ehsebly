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
            'currency' => 'nullable|string|in:' . implode(',', \App\Models\Event::SUPPORTED_CURRENCIES),
            'budget' => 'nullable|numeric|min:0.01',
        ]);

        $event = \App\Models\Event::create([
            'name' => $validated['name'],
            'date' => $validated['date'] ?? null,
            'currency' => $validated['currency'] ?? 'EGP',
            'budget' => $validated['budget'] ?? null,
            'creator_id' => auth()->id()
        ]);

        // Add creator as first participant, and as organizer
        $event->participants()->create([
            'user_id' => auth()->id(),
            'role' => \App\Models\EventParticipant::ROLE_ORGANIZER,
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

    public function toggleParticipantRole(\App\Models\Event $event, \App\Models\EventParticipant $participant)
    {
        if ($event->creator_id !== auth()->id()) {
            abort(403);
        }

        if ($participant->event_id !== $event->id) {
            abort(404);
        }

        if ($participant->user_id === $event->creator_id) {
            return back()->withErrors(['role' => 'The event creator is always the organizer.']);
        }

        if (!$participant->user_id) {
            return back()->withErrors(['role' => 'Guests cannot be made organizers.']);
        }

        $newRole = $participant->isOrganizer()
            ? \App\Models\EventParticipant::ROLE_MEMBER
            : \App\Models\EventParticipant::ROLE_ORGANIZER;

        $participant->update(['role' => $newRole]);

        return back()->with('status', $newRole === \App\Models\EventParticipant::ROLE_ORGANIZER ? 'Promoted to organizer.' : 'Organizer role removed.');
    }

    public function exportPdf(string $id)
    {
        $event = \App\Models\Event::with([
            'participants.user',
            'creator',
            'expenses.payers.participant.user',
        ])->findOrFail($id);

        $isParticipant = $event->participants->contains('user_id', auth()->id());
        if (!$isParticipant && $event->creator_id !== auth()->id()) {
            abort(403, 'You are not a participant of this event.');
        }

        $settlements = \App\Models\Settlement::where('event_id', $event->id)
            ->with(['fromParticipant.user', 'toParticipant.user'])
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('events.pdf', compact('event', 'settlements'));

        return $pdf->download(\Illuminate\Support\Str::slug($event->name) . '-report.pdf');
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
