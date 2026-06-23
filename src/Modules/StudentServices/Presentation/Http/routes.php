<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\StudentServices\Presentation\Http\Controllers\AssistantController;
use Modules\StudentServices\Presentation\Http\Controllers\DashboardController;
use Modules\StudentServices\Presentation\Http\Controllers\DocumentController;
use Modules\StudentServices\Presentation\Http\Controllers\FaqController;
use Modules\StudentServices\Presentation\Http\Controllers\KnowledgeController;
use Modules\StudentServices\Presentation\Http\Controllers\ServiceRequestController;

Route::middleware(['web'])->group(function (): void {

    // Authenticated student routes
    Route::middleware(['auth', 'role:student'])->prefix('student-services')->name('student-services.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Service Requests
        Route::get('/requests', [ServiceRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [ServiceRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');
        Route::get('/requests/{id}', [ServiceRequestController::class, 'show'])->name('requests.show');
        Route::post('/requests/{id}/approve', [ServiceRequestController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [ServiceRequestController::class, 'reject'])->name('requests.reject');
        Route::post('/requests/{id}/cancel', [ServiceRequestController::class, 'cancel'])->name('requests.cancel');

        // Services catalog
        Route::get('/services', [ServiceRequestController::class, 'services'])->name('services.index');

        // Documents
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');

        // Knowledge
        Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
        Route::get('/knowledge/search', [KnowledgeController::class, 'search'])->name('knowledge.search');
        Route::get('/knowledge/{id}', [KnowledgeController::class, 'show'])->name('knowledge.show');

        // FAQ
        Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

        // Assistant
        Route::get('/assistant', [AssistantController::class, 'chat'])->name('assistant.chat');
        Route::post('/assistant/send', [AssistantController::class, 'send'])->name('assistant.send');
        Route::get('/assistant/history', [AssistantController::class, 'history'])->name('assistant.history');
        Route::get('/assistant/{id}', [AssistantController::class, 'history'])->name('assistant.conversation');
    });

    // Public routes
    Route::get('/verify-document/{code}', [DocumentController::class, 'verify'])->name('student-services.documents.verify');
});
