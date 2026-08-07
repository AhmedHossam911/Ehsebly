<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecurringPaymentController extends Controller
{
    public function index()
    {
        $recurringPayments = auth()->user()->recurringPayments()->orderBy('due_date')->get();

        return view('recurring-payments.index', compact('recurringPayments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'recurrence_type' => 'required|in:daily,weekly,monthly,yearly',
        ]);

        auth()->user()->recurringPayments()->create($validated);

        return back()->with('status', 'Recurring payment added successfully!');
    }

    public function destroy(\App\Models\RecurringPayment $recurringPayment)
    {
        if ($recurringPayment->user_id !== auth()->id()) {
            abort(403);
        }

        $recurringPayment->delete();

        return back()->with('status', 'Recurring payment removed.');
    }
}
