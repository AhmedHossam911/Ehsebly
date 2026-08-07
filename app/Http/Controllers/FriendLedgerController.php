<?php

namespace App\Http\Controllers;

use App\Models\FriendTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class FriendLedgerController extends Controller
{
    public function store(Request $request, User $user)
    {
        if ($user->id === auth()->id() || !auth()->user()->friends->contains($user->id)) {
            abort(403);
        }

        $request->validate([
            'direction' => 'required|in:lent,borrowed',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        FriendTransaction::create([
            'lender_id' => $request->direction === 'lent' ? auth()->id() : $user->id,
            'borrower_id' => $request->direction === 'lent' ? $user->id : auth()->id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return back()->with('status', 'Transaction added.');
    }

    public function destroy(FriendTransaction $transaction)
    {
        if (!in_array(auth()->id(), [$transaction->lender_id, $transaction->borrower_id])) {
            abort(403);
        }

        $transaction->delete();

        return back()->with('status', 'Transaction deleted.');
    }
}
