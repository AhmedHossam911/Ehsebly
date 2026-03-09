<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Settlement;
use App\Models\ExpenseSplit;
use Illuminate\Support\Facades\DB;

class DebtCalculatorService
{
    /**
     * Calculate simplified debts for an event.
     * Uses a greedy algorithm to minimize transactions mapping positive balances to negative balances.
     */
    public function calculateForEvent(Event $event)
    {
        // Get all expenses, payers, and splits for this event
        $expenses = $event->expenses()->with(['splits', 'payers'])->get();

        // balances map: participant_id => balance (positive means they are owed, negative means they owe)
        $balances = [];

        foreach ($event->participants as $participant) {
            $balances[$participant->id] = 0;
        }

        foreach ($expenses as $expense) {
            // The people who paid getting credited
            foreach ($expense->payers as $payer) {
                if (isset($balances[$payer->participant_id])) {
                    $balances[$payer->participant_id] += $payer->amount;
                }
            }

            // The people involved in the split getting debited
            foreach ($expense->splits as $split) {
                if (isset($balances[$split->participant_id])) {
                    $balances[$split->participant_id] -= $split->amount;
                }
            }
        }

        // Simplify debts
        $debtors = [];
        $creditors = [];

        foreach ($balances as $participantId => $balance) {
            if ($balance < -0.01) {
                $debtors[] = ['id' => $participantId, 'amount' => abs($balance)];
            }
            elseif ($balance > 0.01) {
                $creditors[] = ['id' => $participantId, 'amount' => $balance];
            }
        }

        // Sort descending by amount to optimize the greedy matching
        usort($debtors, fn($a, $b) => $b['amount'] <=> $a['amount']);
        usort($creditors, fn($a, $b) => $b['amount'] <=> $a['amount']);

        $settlements = [];
        $i = 0; // debtor index
        $j = 0; // creditor index

        while ($i < count($debtors) && $j < count($creditors)) {
            $debt = $debtors[$i]['amount'];
            $credit = $creditors[$j]['amount'];

            $settleAmount = min($debt, $credit);

            $settlements[] = [
                'event_id' => $event->id,
                'from_participant_id' => $debtors[$i]['id'],
                'to_participant_id' => $creditors[$j]['id'],
                'amount' => round($settleAmount, 2),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $debtors[$i]['amount'] -= $settleAmount;
            $creditors[$j]['amount'] -= $settleAmount;

            if ($debtors[$i]['amount'] < 0.01) {
                $i++;
            }
            if ($creditors[$j]['amount'] < 0.01) {
                $j++;
            }
        }

        // Save settlements to database (wipe old ones first)
        DB::transaction(function () use ($event, $settlements) {
            Settlement::where('event_id', $event->id)->where('status', 'pending')->delete();
            Settlement::insert($settlements);
        });

        return $settlements;
    }
}
