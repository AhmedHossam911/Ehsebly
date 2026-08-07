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

        // No OCR service is configured yet.
        return response()->json([
            'success' => false,
            'message' => 'Receipt scanning is coming soon.',
        ], 503);
    }
}
