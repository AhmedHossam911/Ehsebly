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
        // Grouped by currency: events can now use different currencies, so a single blended total would be meaningless.
        $youOwe = Settlement::where('status', 'pending')
            ->whereHas('fromParticipant', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('event')->get()
            ->groupBy(fn($settlement) => $settlement->event->currency ?? 'EGP')
            ->map(fn($group) => $group->sum('amount'));

        // Calculate how much the user is owed by others (pending settlements where user is the 'to' party)
        $youAreOwed = Settlement::where('status', 'pending')
            ->whereHas('toParticipant', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('event')->get()
            ->groupBy(fn($settlement) => $settlement->event->currency ?? 'EGP')
            ->map(fn($group) => $group->sum('amount'));

        return view('dashboard', compact('youOwe', 'youAreOwed'));
    }
}
