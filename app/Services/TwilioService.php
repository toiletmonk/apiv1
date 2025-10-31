<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
    }

    public function sendMessage(int $number, string $message)
    {
        return $this->client->messages->create($number, [
            'from' => env('TWILIO_NUMBER'),
            'body' => $message,
        ]);
    }
}
