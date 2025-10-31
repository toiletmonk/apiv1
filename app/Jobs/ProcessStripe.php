<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use App\Services\StoreTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class ProcessStripe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $event) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $status = PaymentStatus::stripeEvents($this->event->type);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return;
        }
        $paymentIntent = $this->event->data->object;
        $userId = $paymentIntent->metadata->user_id ?? null;

        if (! $userId || ! User::where('id', $userId)->exists()) {
            Log::warning('Payment not possible for unexisting user.');

            return;
        }

        $common = [
            'payment_status' => $paymentIntent->status,
            'total_amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'user_id' => $userId,
        ];
        $validator = Validator::make($common, [
            'payment_status' => ['required', new Enum(PaymentStatus::class)],
            'total_amount' => 'required|numeric|min:10',
            'currency' => 'required|in:usd,eur',
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            Log::channel('stripe')->error($validator->errors());

            return;
        }
        try {
            DB::transaction(function () use ($common, $paymentIntent) {
                Order::updateOrCreate(
                    ['payment_intent_id' => $paymentIntent->id],
                    $common
                );
                app(StoreTransaction::class)->saveTransactionToDB($common['user_id'], $paymentIntent);
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        if ($status->isSuccessful()) {
            SendReceiptMail::dispatch($paymentIntent->id);
        }
        if ($status->isFailed()) {
            Log::warning('Payment failed.');
        }
        if ($status->isCanceled()) {
            Log::warning('Payment canceled.');
        }
    }
}
