<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Presentation\Http\Controllers\NotificationController;

Route::middleware(['web', 'auth', 'role:student'])->prefix('notifications')->name('notifications.')->group(function (): void {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
});
