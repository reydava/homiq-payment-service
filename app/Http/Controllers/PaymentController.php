<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // POST /payments
    public function store(Request $request)
    {
        $payment = Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'status' => 'SUCCESS'
        ]);

        return response()->json($payment, 201);
    }

    // GET /payments/{id}
    public function show($id)
    {
        return response()->json(
            Payment::findOrFail($id)
        );
    }

    // GET /payments/order/{orderId}
    public function getByOrder($orderId)
    {
        return response()->json(
            Payment::where('order_id', $orderId)->first()
        );
    }
}