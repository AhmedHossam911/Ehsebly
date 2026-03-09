<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request, \App\Models\Event $event)
    {
        // Must be a participant or creator
        $isParticipant = $event->participants->contains('user_id', auth()->id());
        $isCreator = $event->creator_id === auth()->id();
        $isGuestSession = session()->has('guest_participant_' . $event->id);

        if (!$isParticipant && !$isCreator && !$isGuestSession) {
            abort(403);
        }

        if ($event->isLocked()) {
            return back()->withErrors(['expense' => 'Cannot modify expenses. Payments have already started.']);
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01',
            'payers' => 'required|array|min:1', // Array of [participant_id => amount]
            'split_type' => 'required|in:equal,custom',
            'participants' => 'required|array', // List of participant IDs involved in the split
            'amounts' => 'array', // Required if custom max constraints
        ]);

        $payerSum = array_sum(array_column($request->payers, 'amount'));
        if (abs($payerSum - $request->total_amount) > 0.01) {
            return back()->withErrors(['total_amount' => 'The sum of payers must equal the total amount.'])->withInput();
        }

        $expense = $event->expenses()->create([
            'description' => $request->description,
            'total_amount' => $request->total_amount,
            'date' => now(),
        ]);

        foreach ($request->payers as $payerData) {
            if ($payerData['amount'] > 0) {
                $expense->payers()->create([
                    'participant_id' => $payerData['participant_id'],
                    'amount' => $payerData['amount'],
                ]);
            }
        }

        $participants = $request->participants;

        if ($request->split_type === 'equal') {
            $splitAmount = $request->total_amount / count($participants);
            foreach ($participants as $pId) {
                $expense->splits()->create([
                    'participant_id' => $pId,
                    'amount' => $splitAmount,
                ]);
            }
        }
        elseif ($request->split_type === 'custom') {
            $amountsList = collect($request->amounts);
            foreach ($participants as $pId) {
                $pData = $amountsList->firstWhere('participant_id', $pId);
                $amount = is_array($pData) && isset($pData['amount']) ? $pData['amount'] : 0;

                $expense->splits()->create([
                    'participant_id' => $pId,
                    'amount' => $amount,
                ]);
            }
        }

        // Recalculate Debts
        $calculator = new \App\Services\DebtCalculatorService();
        $calculator->calculateForEvent($event);

        // Notify all participants (who are registered users) except the creator/adder
        $adderName = auth()->user()->name ?? 'A guest';
        foreach ($event->participants as $participant) {
            if ($participant->user_id && $participant->user_id !== auth()->id()) {
                $participant->user->notify(new \App\Notifications\ExpenseAddedNotification($expense, $event));
            }
        }

        if ($isGuestSession) {
            return redirect()->route('guest.dashboard', $event->guest_token)->with('status', 'Expense added and debts calculated!');
        }

        return redirect()->route('events.show', $event)->with('status', 'Expense added and debts calculated!');
    }

    public function edit(\App\Models\Event $event, \App\Models\Expense $expense)
    {
        if ($event->isLocked()) {
            return back()->withErrors(['expense' => 'Cannot modify expenses. Payments have already started.']);
        }

        // Must be creator or part of the expense payers to edit it (or just participant if we allow open editing)
        $isParticipant = $event->participants->contains('user_id', auth()->id());
        if (!$isParticipant && $event->creator_id !== auth()->id()) {
            abort(403);
        }

        $expense->load(['payers', 'splits']);
        $event->load('participants.user');

        return view('expenses.edit', compact('event', 'expense'));
    }

    public function update(Request $request, \App\Models\Event $event, \App\Models\Expense $expense)
    {
        if ($event->isLocked()) {
            return back()->withErrors(['expense' => 'Cannot modify expenses. Payments have already started.']);
        }

        $isParticipant = $event->participants->contains('user_id', auth()->id());
        if (!$isParticipant && $event->creator_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01',
            'payers' => 'required|array|min:1',
            'split_type' => 'required|in:equal,custom',
            'participants' => 'required|array',
            'amounts' => 'array',
        ]);

        $payerSum = array_sum(array_column($request->payers, 'amount'));
        if (abs($payerSum - $request->total_amount) > 0.01) {
            return back()->withErrors(['total_amount' => 'The sum of payers must equal the total amount.'])->withInput();
        }

        $expense->update([
            'description' => $request->description,
            'total_amount' => $request->total_amount,
        ]);

        // Wipe old payers and splits
        $expense->payers()->delete();
        $expense->splits()->delete();

        foreach ($request->payers as $payerData) {
            if ($payerData['amount'] > 0) {
                $expense->payers()->create([
                    'participant_id' => $payerData['participant_id'],
                    'amount' => $payerData['amount'],
                ]);
            }
        }

        $participants = $request->participants;

        if ($request->split_type === 'equal') {
            $splitAmount = $request->total_amount / count($participants);
            foreach ($participants as $pId) {
                $expense->splits()->create([
                    'participant_id' => $pId,
                    'amount' => $splitAmount,
                ]);
            }
        }
        elseif ($request->split_type === 'custom') {
            $amountsList = collect($request->amounts);
            foreach ($participants as $pId) {
                $pData = $amountsList->firstWhere('participant_id', $pId);
                $amount = is_array($pData) && isset($pData['amount']) ? $pData['amount'] : 0;

                $expense->splits()->create([
                    'participant_id' => $pId,
                    'amount' => $amount,
                ]);
            }
        }

        // Recalculate Debts
        $calculator = new \App\Services\DebtCalculatorService();
        $calculator->calculateForEvent($event);

        return redirect()->route('events.show', $event)->with('status', 'Expense updated and debts recalculated!');
    }

    public function destroy(\App\Models\Event $event, \App\Models\Expense $expense)
    {
        if ($event->isLocked()) {
            return back()->withErrors(['expense' => 'Cannot modify expenses. Payments have already started.']);
        }

        $expense->delete();

        // Recalculate Debts
        $calculator = new \App\Services\DebtCalculatorService();
        $calculator->calculateForEvent($event);

        return back()->with('status', 'Expense removed and debts updated.');
    }
}
