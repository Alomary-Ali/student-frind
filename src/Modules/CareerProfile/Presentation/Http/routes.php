<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\CareerProfile\Presentation\Http\Controllers\CareerProfileController;

Route::middleware(['web', 'auth', 'role:student'])
    ->prefix('career')
    ->name('career.')
    ->group(function () {
        Route::get('/', [CareerProfileController::class, 'index'])->name('index');
        Route::post('/update', [CareerProfileController::class, 'update'])->name('update')->middleware('throttle:30,1');
        Route::post('/portfolio', [CareerProfileController::class, 'storePortfolioItem'])->name('portfolio.store')->middleware('throttle:30,1');
        Route::post('/experience', [CareerProfileController::class, 'storeExperience'])->name('experience.store')->middleware('throttle:30,1');
        Route::post('/goals', [CareerProfileController::class, 'storeCareerGoal'])->name('goals.store')->middleware('throttle:30,1');
    });
