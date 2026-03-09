<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReceiptScannerController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|max:5120', // Max 5MB
        ]);

        // Simulating 2-second processing delay for OCR
        sleep(2);

        // Mocked response for receipt extraction
        return response()->json([
            'success' => true,
            'data' => [
                'merchant' => 'Mince Burgers',
                'date' => now()->format('Y-m-d'),
                'total' => 640.00,
                'items' => [
                    ['description' => 'Classic Burger', 'amount' => 180.00],
                    ['description' => 'Mushroom Swiss', 'amount' => 220.00],
                    ['description' => 'Large Fries', 'amount' => 50.00],
                    ['description' => 'Soda x2', 'amount' => 60.00],
                    ['description' => 'Service Charge (12%)', 'amount' => 61.20],
                    ['description' => 'Taxes (14%)', 'amount' => 68.80],
                ]
            ]
        ]);
    }
}
