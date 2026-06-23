<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Opportunities\Presentation\Http\Controllers\OpportunitiesController;

Route::middleware(['web', 'auth', 'role:student'])
    ->prefix('opportunities')
    ->name('opportunities.')
    ->group(function () {
        Route::get('/', [OpportunitiesController::class, 'index'])->name('index');
        Route::get('/recommended', [OpportunitiesController::class, 'recommended'])->name('recommended');
        Route::get('/saved', [OpportunitiesController::class, 'saved'])->name('saved');
        Route::get('/applications', [OpportunitiesController::class, 'applications'])->name('applications');
        Route::get('/scholarships', [OpportunitiesController::class, 'scholarships'])->name('scholarships');
        Route::get('/jobs', [OpportunitiesController::class, 'jobs'])->name('jobs');
        Route::get('/internships', [OpportunitiesController::class, 'internships'])->name('internships');
        Route::get('/courses', [OpportunitiesController::class, 'courses'])->name('courses');
        Route::get('/competitions', [OpportunitiesController::class, 'competitions'])->name('competitions');
        Route::post('/save', [OpportunitiesController::class, 'save'])->name('save');
        Route::post('/apply', [OpportunitiesController::class, 'apply'])->name('apply');
    });
