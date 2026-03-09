<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->walletTransactions()->latest('date')->get();

        $balance = $transactions->reduce(function ($carry, $transaction) {
            return $transaction->type === 'income'
            ? $carry + $transaction->amount
            : $carry - $transaction->amount;
        }, 0);

        return view('wallet.index', compact('transactions', 'balance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->walletTransactions()->create($validated);

        return back()->with('status', 'Transaction added successfully!');
    }
}
