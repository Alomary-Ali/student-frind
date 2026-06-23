<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Career\Presentation\Http\Controllers\CareerPathController;
use Modules\Career\Presentation\Http\Controllers\DashboardController;
use Modules\Career\Presentation\Http\Controllers\InterviewController;
use Modules\Career\Presentation\Http\Controllers\PortfolioController;

Route::middleware(['web'])->group(function (): void {

    Route::middleware(['auth', 'role:student'])->prefix('career')->name('career.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');
        Route::post('/interviews/schedule', [InterviewController::class, 'schedule'])->name('interviews.schedule')->middleware('throttle:30,1');
        Route::get('/interviews/{id}', [InterviewController::class, 'show'])->name('interviews.show');
        Route::post('/interviews/{id}/submit', [InterviewController::class, 'submit'])->name('interviews.submit')->middleware('throttle:10,1');

        Route::get('/paths', [CareerPathController::class, 'index'])->name('paths.index');
        Route::get('/paths/{id}', [CareerPathController::class, 'show'])->name('paths.show');
        Route::get('/paths/recommendations', [CareerPathController::class, 'recommendations'])->name('paths.recommendations');

        Route::get('/readiness', [DashboardController::class, 'readiness'])->name('readiness');
        Route::get('/recommendations', [DashboardController::class, 'recommendations'])->name('recommendations');

        Route::get('/portfolio/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
        Route::post('/portfolio/publish', [PortfolioController::class, 'update'])->name('portfolio.update')->middleware('throttle:10,1');
    });

    Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.public');
});
