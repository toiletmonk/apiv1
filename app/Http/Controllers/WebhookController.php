<?php

namespace App\Http\Controllers;

use App\Services\WebhookService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function process(Request $request)
    {
        $this->webhookService->processWebhook($request);

        return response()->json(['status' => 'success']);
    }
}
