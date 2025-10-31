<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment')
    ->middleware(['auth:sanctum', 'check-expiration', 'throttle:payment'])
    ->group(function () {
        Route::apiResource('/', PaymentController::class)
            ->only(['store', 'show', 'index']);
        Route::post('{payment}/confirm', [PaymentController::class, 'confirm']);
    });
