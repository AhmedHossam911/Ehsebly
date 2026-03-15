<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Calculate how much the user owes others (pending settlements where user is the 'from' party)
        // We look at EventParticipant where user_id matches, and sum settlements where from_participant_id matches those.
        $youOwe = Settlement::where('status', 'pending')
            ->whereHas('fromParticipant', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->sum('amount');

        // Calculate how much the user is owed by others (pending settlements where user is the 'to' party)
        $youAreOwed = Settlement::where('status', 'pending')
            ->whereHas('toParticipant', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->sum('amount');

        return view('dashboard', compact('youOwe', 'youAreOwed'));
    }
}
