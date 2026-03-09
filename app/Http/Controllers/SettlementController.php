<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function pay(\App\Models\Settlement $settlement)
    {
        // Only the debtor can initiate payment if they have a real account
        if ($settlement->fromParticipant->user_id !== auth()->id()) {
            abort(403);
        }

        $creditor = $settlement->toParticipant->user;

        $settlement->update(['status' => \App\Models\Settlement::STATUS_PAID]);

        // Notify Creditor
        if ($creditor) {
            $payerName = $settlement->fromParticipant->user->name ?? $settlement->fromParticipant->guest_name;
            $creditor->notify(new \App\Notifications\SettlementPaidNotification($settlement->amount, $payerName, $settlement->event));
        }

        if ($creditor && !empty($creditor->instapay_link)) {
            return redirect()->away($creditor->instapay_link);
        }

        return back()->with('status', 'Marked as paid. Waiting for confirmation.');
    }

    public function confirm(\App\Models\Settlement $settlement)
    {
        // Only the creditor can confirm payment was received
        if ($settlement->toParticipant->user_id !== auth()->id()) {
            abort(403);
        }

        $settlement->update(['status' => \App\Models\Settlement::STATUS_CONFIRMED]);

        // Notify Debtor
        $debtor = $settlement->fromParticipant->user;
        if ($debtor) {
            $creditorName = $settlement->toParticipant->user->name ?? $settlement->toParticipant->guest_name;
            $debtor->notify(new \App\Notifications\SettlementConfirmedNotification($settlement->amount, $creditorName, $settlement->event));
        }

        return back()->with('status', 'Payment receipt confirmed. Debt settled.');
    }
}
