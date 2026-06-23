<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Academic\Presentation\Controllers\AssignAcademicPlanController;
use Modules\Academic\Presentation\Controllers\CreateCourseController;
use Modules\Academic\Presentation\Controllers\CreateSemesterPlanController;
use Modules\Academic\Presentation\Controllers\CreateStudentController;
use Modules\Academic\Presentation\Controllers\EnrollStudentController;
use Modules\Academic\Presentation\Controllers\GetGraduationProgressController;
use Modules\Academic\Presentation\Controllers\GetStudentController;
use Modules\Academic\Presentation\Controllers\ListCoursesController;
use Modules\Academic\Presentation\Controllers\RecordGradeController;

Route::prefix('api/v1/academic')->group(function () {
    // Public routes (if any) - currently none for academic module

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        // Student can view their own data, admin/advisor can view any
        Route::get('/students/{studentId}', GetStudentController::class)
            ->middleware('throttle:60,1')
            ->can('view', 'studentId');
        Route::get('/students/{studentId}/graduation-progress', GetGraduationProgressController::class)
            ->middleware('throttle:60,1')
            ->can('view', 'studentId');

        // Course list (requires authentication)
        Route::get('/courses', ListCoursesController::class)
            ->middleware('throttle:60,1');

        // Admin/Advisor only routes
        Route::middleware('role:admin,advisor')->group(function () {
            Route::post('/students', CreateStudentController::class)
                ->middleware('throttle:30,1');
            Route::post('/courses', CreateCourseController::class)
                ->middleware('throttle:30,1');
            Route::post('/plans', AssignAcademicPlanController::class)
                ->middleware('throttle:30,1');
            Route::post('/semester-plans', CreateSemesterPlanController::class)
                ->middleware('throttle:30,1');
            Route::post('/enrollments', EnrollStudentController::class)
                ->middleware('throttle:30,1');
            Route::post('/records', RecordGradeController::class)
                ->middleware('throttle:30,1');
        });
    });
});
