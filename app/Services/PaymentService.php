<?php

namespace App\Services;

use App\Models\Payment;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createPaymentIntent(?int $amount, string $currency, int $userId, $metadata = []): PaymentIntent
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => ['card'],
            'metadata' => array_merge($metadata, ['user_id' => $userId]),
        ]);
    }

    public function calculateAmount($cartItems)
    {
        return $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->post->price;
        });
    }

    public function confirm(Payment $payment)
    {
        $paymentIntent = PaymentIntent::retrieve($payment->stripe_payment_intent_id);
        $paymentIntent->confirm();

        $payment->status = $paymentIntent->status;
        $payment->save();

        return $payment;
    }
}
