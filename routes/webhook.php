<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhook')
    ->group(function () {
        Route::post('/webhook', [WebhookController::class, 'process']);
    });
