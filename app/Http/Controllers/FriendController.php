<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        $friends = auth()->user()->friends()->get();
        return view('friends.index', compact('friends'));
    }

    public function destroy($friendId)
    {
        // Delete mutual friendship
        \App\Models\Friend::where('user_id', auth()->id())
            ->where('friend_id', $friendId)
            ->delete();

        \App\Models\Friend::where('user_id', $friendId)
            ->where('friend_id', auth()->id())
            ->delete();

        return back()->with('status', 'Friend removed.');
    }
}
