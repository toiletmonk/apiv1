<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\CartService;
use App\Services\PaymentService;
use App\Services\StoreTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected PaymentService $paymentService,
        protected CartService $cartService,
        protected StoreTransaction $storeTransaction
    ) {}

    public function store(Request $request)
    {
        $user = $request->user();
        $cartItems = $this->cartService->getAllCartItems();

        if (empty($cartItems)) {
            return response()->json(['message' => 'Cart is empty']);
        }

        $amount = $this->paymentService->calculateAmount($cartItems);

        $paymentIntent = $this->paymentService->createPaymentIntent(
            $amount,
            'usd,eur',
            $user->id
        );

        $this->storeTransaction->saveTransactionToDB($user->id, $paymentIntent);

        return response()->json(['client_secret' => $paymentIntent->client_secret]);
    }

    public function confirm(Payment $payment)
    {
        $this->paymentService->confirm($payment);
        $payment->status = 'confirmed';
        $payment->save();

        return response()->json(['message' => 'Payment confirmed']);
    }

    public function index(Request $request)
    {
        $payments = $request->user()->payments()->latest()->get();

        return response()->json($payments);
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        return response()->json($payment);
    }
}
