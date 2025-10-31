<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('files')->middleware(['auth:sanctum', 'throttle:auth'])->group(function () {
    Route::post('/', [UploadController::class, 'upload']);
    Route::delete('/{file}', [UploadController::class, 'delete']);
});

