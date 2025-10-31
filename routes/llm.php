<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::prefix('llm')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/', [ChatController::class, 'chat']);
    });
