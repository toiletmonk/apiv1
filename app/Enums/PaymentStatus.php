<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Succeeded = 'succeeded';
    case Canceled = 'canceled';
    case Failed = 'failed';

    public static function stripeEvents(string $eventType): self
    {
        return match ($eventType) {
            'payment_intent.succeeded' => self::Succeeded,
            'payment_intent.canceled' => self::Failed,
            'payment_intent.payment_failed' => self::Failed,
            default => throw new \Exception('Unknown event type'),
        };
    }

    public function isSuccessful(): bool
    {
        return $this === self::Succeeded;
    }

    public function isCanceled(): bool
    {
        return $this === self::Canceled;
    }

    public function isFailed(): bool
    {
        return $this === self::Failed;
    }
}
