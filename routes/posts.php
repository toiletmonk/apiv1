<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('posts')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::apiResource('/', PostController::class);
    });
