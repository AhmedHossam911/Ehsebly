<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()->friendRequestsReceived()->with('sender')->where('status', 'pending')->get();
        // Return a view later, placeholder for now
        return view('friends.requests', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string' // can be uid or phone_number
        ]);

        $receiver = \App\Models\User::where('uid', $request->identifier)
            ->orWhere('phone_number', $request->identifier)
            ->first();

        if (!$receiver) {
            return back()->withErrors(['identifier' => 'User not found.']);
        }

        if ($receiver->id === auth()->id()) {
            return back()->withErrors(['identifier' => 'You cannot send a request to yourself.']);
        }

        // Check if already friends
        $alreadyFriends = \App\Models\Friend::where('user_id', auth()->id())
            ->where('friend_id', $receiver->id)
            ->exists();

        if ($alreadyFriends) {
            return back()->withErrors(['identifier' => 'You are already friends.']);
        }

        // Check if a request already exists
        $existingRequest = \App\Models\FriendRequest::where('sender_id', auth()->id())
            ->where('receiver_id', $receiver->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return back()->with('status', 'Friend request already sent.');
        }

        \App\Models\FriendRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'status' => 'pending'
        ]);

        return back()->with('status', 'Friend request sent!');
    }

    public function accept(\App\Models\FriendRequest $friendRequest)
    {
        // Must be the receiver
        if ($friendRequest->receiver_id !== auth()->id()) {
            abort(403);
        }

        $friendRequest->update(['status' => 'accepted']);

        // Create mutual friendship
        \App\Models\Friend::create([
            'user_id' => $friendRequest->sender_id,
            'friend_id' => $friendRequest->receiver_id,
            'status' => 'accepted'
        ]);

        \App\Models\Friend::create([
            'user_id' => $friendRequest->receiver_id,
            'friend_id' => $friendRequest->sender_id,
            'status' => 'accepted'
        ]);

        return back()->with('status', 'Friend request accepted!');
    }

    public function reject(\App\Models\FriendRequest $friendRequest)
    {
        if ($friendRequest->receiver_id !== auth()->id()) {
            abort(403);
        }

        $friendRequest->update(['status' => 'rejected']);

        return back()->with('status', 'Friend request rejected.');
    }
}
