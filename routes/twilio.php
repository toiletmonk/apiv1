<?php

use App\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:auth'])->group(function () {
    Route::post('/sendVerificationSms', [SMSController::class, 'sendCode']);
});
