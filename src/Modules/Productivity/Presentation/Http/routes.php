<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Productivity\Presentation\Http\Controllers\CalendarEventController;
use Modules\Productivity\Presentation\Http\Controllers\GoalController;
use Modules\Productivity\Presentation\Http\Controllers\ProductivityDashboardController;
use Modules\Productivity\Presentation\Http\Controllers\ReminderController;
use Modules\Productivity\Presentation\Http\Controllers\TaskController;

Route::prefix('api/v1/productivity')->middleware(['auth:sanctum'])->group(function () {
    // Goals
    Route::post('/goals', [GoalController::class, 'store'])->middleware('throttle:30,1');
    Route::get('/goals/{id}', [GoalController::class, 'show'])->middleware('throttle:60,1');
    Route::get('/users/{userId}/goals', [GoalController::class, 'index'])->middleware('throttle:60,1');
    Route::patch('/goals/{id}/progress', [GoalController::class, 'updateProgress'])->middleware('throttle:30,1');

    // Tasks
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('throttle:30,1');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->middleware('throttle:60,1');
    Route::get('/users/{userId}/tasks', [TaskController::class, 'index'])->middleware('throttle:60,1');
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->middleware('throttle:30,1');

    // Reminders
    Route::post('/reminders', [ReminderController::class, 'store'])->middleware('throttle:30,1');
    Route::get('/users/{userId}/reminders', [ReminderController::class, 'index'])->middleware('throttle:60,1');

    // Calendar Events
    Route::post('/calendar-events', [CalendarEventController::class, 'store'])->middleware('throttle:30,1');
    Route::get('/users/{userId}/calendar-events', [CalendarEventController::class, 'index'])->middleware('throttle:60,1');

    // Dashboard
    Route::get('/users/{userId}/dashboard', [ProductivityDashboardController::class, 'show'])->middleware('throttle:60,1');
    Route::post('/users/{userId}/snapshots', [ProductivityDashboardController::class, 'generateSnapshot'])->middleware('throttle:30,1');
});
