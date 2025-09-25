<?php

namespace App\Http\Controllers\Customer;

use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function create(Event $event)
    {
        return view('customers.payments.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'payment_receipt' => 'required|file|mimes:jpg,png,pdf|max:10240',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $filePath = $request->file('payment_receipt')->store('payment_receipts', 'public');

        $billing = $event->billing;

        if (!$billing) {
            return back()->with('error', 'Billing information not found for this event.');
        }

        Payment::create([
            'billing_id' => $billing->id,
            'payment_image' => $filePath,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'status' => 'pending',
            'payment_date' => now(),
        ]);

        $event->update([
            'status' => 'request_meeting',
        ]);

        return redirect()->route('customer.events.show', $event)->with('success', 'Payment proof submitted successfully.');
    }
}
