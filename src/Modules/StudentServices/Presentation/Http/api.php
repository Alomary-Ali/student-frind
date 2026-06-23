<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\StudentServices\Presentation\Http\Controllers\AssistantController;
use Modules\StudentServices\Presentation\Http\Controllers\DocumentController;
use Modules\StudentServices\Presentation\Http\Controllers\KnowledgeController;
use Modules\StudentServices\Presentation\Http\Controllers\ServiceRequestController;

Route::middleware(['auth:sanctum'])->prefix('api')->group(function (): void {
    Route::apiResource('service-requests', ServiceRequestController::class);
    Route::apiResource('documents', DocumentController::class);
    Route::get('knowledge', [KnowledgeController::class, 'index']);
    Route::get('assistant/conversations', [AssistantController::class, 'index']);
    Route::post('assistant/conversations', [AssistantController::class, 'start']);
    Route::post('assistant/conversations/{id}/messages', [AssistantController::class, 'send']);
});
