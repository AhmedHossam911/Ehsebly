<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // What the user owes (user is the 'from' participant)
        $payables = Settlement::with(['event', 'toParticipant.user'])
            ->where('status', 'pending')
            ->whereHas('fromParticipant', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        // What the user is owed (user is the 'to' participant)
        $receivables = Settlement::with(['event', 'fromParticipant.user'])
            ->where('status', 'pending')
            ->whereHas('toParticipant', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        return view('debts.index', compact('payables', 'receivables'));
    }
}
