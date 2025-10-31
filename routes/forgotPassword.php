<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
