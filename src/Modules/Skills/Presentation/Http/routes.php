<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Skills\Presentation\Http\Controllers\SkillsController;

Route::middleware(['web', 'auth', 'role:student'])
    ->prefix('skills')
    ->name('skills.')
    ->group(function () {
        Route::get('/', [SkillsController::class, 'index'])->name('index');
        Route::post('/skills', [SkillsController::class, 'storeSkill'])->name('skills.store')->middleware('throttle:30,1');
        Route::post('/certifications', [SkillsController::class, 'storeCertification'])->name('certifications.store')->middleware('throttle:30,1');
    });
