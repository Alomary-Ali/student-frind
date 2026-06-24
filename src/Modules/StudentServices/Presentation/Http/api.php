<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\StudentServices\Presentation\Http\Controllers\Api\AssistantApiController;
use Modules\StudentServices\Presentation\Http\Controllers\Api\DocumentApiController;
use Modules\StudentServices\Presentation\Http\Controllers\Api\KnowledgeApiController;
use Modules\StudentServices\Presentation\Http\Controllers\Api\ServiceRequestApiController;
use Modules\StudentServices\Presentation\Http\Controllers\Api\WorkflowApiController;

Route::middleware(['auth:sanctum'])->prefix('api/v1/student-services')->name('api.student-services.')->group(function (): void {
    Route::get('/requests', [ServiceRequestApiController::class, 'index'])->name('requests.index');
    Route::post('/requests', [ServiceRequestApiController::class, 'store'])->name('requests.store');

    Route::get('/documents', [DocumentApiController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentApiController::class, 'store'])->name('documents.store');
    Route::post('/documents/verify', [DocumentApiController::class, 'verify'])->name('documents.verify');

    Route::get('/knowledge', [KnowledgeApiController::class, 'index'])->name('knowledge.index');
    Route::get('/knowledge/search', [KnowledgeApiController::class, 'search'])->name('knowledge.search');

    Route::get('/assistant/conversations', [AssistantApiController::class, 'index'])->name('assistant.index');
    Route::post('/assistant/conversations', [AssistantApiController::class, 'start'])->name('assistant.start');
    Route::post('/assistant/conversations/{id}/messages', [AssistantApiController::class, 'send'])->name('assistant.send');

    Route::post('/workflows', [WorkflowApiController::class, 'store'])->name('workflows.store');
    Route::get('/workflows/{id}', [WorkflowApiController::class, 'show'])->name('workflows.show');
});
