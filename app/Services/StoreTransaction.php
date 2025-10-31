<?php

namespace App\Services;

use App\Models\Payment;
use Stripe\PaymentIntent;

class StoreTransaction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function saveTransactionToDB(int $userId, PaymentIntent $paymentIntent)
    {
        return Payment::create([
            'user_id' => $userId,
            'provider' => 'stripe',
            'payment_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'payment_status' => $paymentIntent->status,
        ]);
    }
}
