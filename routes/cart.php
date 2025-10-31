<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart-items')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/add', [CartController::class, 'addToCart']);
        Route::get('/', [CartController::class, 'getAllCartItems']);
        Route::delete('/remove/{id}', [CartController::class, 'removeFromCart']);
    });
