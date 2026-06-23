<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Shared\Presentation\Controllers\LoginController;
use Modules\Shared\Presentation\Controllers\RegisterUserController;
use Modules\Shared\Presentation\Controllers\VerifyEmailController;

Route::prefix('api')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/register', RegisterUserController::class)
            ->middleware('throttle:5,1');
        Route::post('/login', LoginController::class)
            ->middleware('throttle:5,1');
    });
    Route::post('/verify-email', VerifyEmailController::class)
        ->middleware('throttle:10,1');
});
