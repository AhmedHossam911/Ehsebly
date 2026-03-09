<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessRecurringPayments extends Command
{
    protected $signature = 'payments:process-recurring';
    protected $description = 'Process all due recurring payments and log them to wallets';

    public function handle()
    {
        $duePayments = \App\Models\RecurringPayment::where('due_date', '<=', now()->format('Y-m-d'))->get();
        $processed = 0;

        foreach ($duePayments as $payment) {
            // Create a wallet transaction (expense)
            \App\Models\WalletTransaction::create([
                'user_id' => $payment->user_id,
                'amount' => $payment->amount,
                'type' => 'expense',
                'category' => 'Recurring - ' . $payment->title,
                'date' => now(),
                'notes' => 'Auto-processed recurring payment',
            ]);

            // Calculate next due date
            $nextDue = \Carbon\Carbon::parse($payment->due_date);
            switch ($payment->recurrence_type) {
                case 'daily':
                    $nextDue->addDay();
                    break;
                case 'weekly':
                    $nextDue->addWeek();
                    break;
                case 'monthly':
                    $nextDue->addMonth();
                    break;
                case 'yearly':
                    $nextDue->addYear();
                    break;
            }

            $payment->update(['due_date' => $nextDue->format('Y-m-d')]);
            $processed++;
        }

        $this->info("Processed $processed recurring payments.");
    }
}
