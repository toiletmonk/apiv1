<?php

use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('oauth')
    ->middleware('throttle:auth')
    ->group(function () {
        Route::get('redirect/{provider}', [OAuthController::class, 'redirect']);
        Route::get('callback/{provider}', [OAuthController::class, 'callback']);
    });

