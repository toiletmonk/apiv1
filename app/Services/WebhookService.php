<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Jobs\ProcessStripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class WebhookService
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('X-Stripe-Signature');
        $secretKey = env('STRIPE_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secretKey);
        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook error'.$e->getMessage());
            throw new CustomException('Invalid webhook signature', 400);
        }

        ProcessStripe::dispatch($event);

        return response()->json(['message' => 'Webhook queued']);
    }
}
