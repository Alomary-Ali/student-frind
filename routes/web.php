<?php

declare(strict_types=1);

use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Modules\Academic\Presentation\Controllers\AcademicDashboardController;
use Modules\Academic\Presentation\Controllers\AcademicPlanController;
use Modules\Academic\Presentation\Controllers\GetStudentAlertsController;
use Modules\Academic\Presentation\Controllers\ListCoursesController;
use Modules\Academic\Presentation\Controllers\ListCurriculumCoursesController;
use Modules\Productivity\Presentation\Controllers\AssignmentController;
use Modules\Productivity\Presentation\Controllers\ExamController;
use Modules\Productivity\Presentation\Controllers\ProductivityCalendarController;
use Modules\Productivity\Presentation\Controllers\ProductivityDashboardController;
use Modules\Productivity\Presentation\Controllers\ProductivityGoalController;
use Modules\Productivity\Presentation\Controllers\ProductivityReminderController;
use Modules\Productivity\Presentation\Controllers\ProductivityTaskController;
use Modules\Productivity\Presentation\Controllers\ProjectController;
use Modules\Shared\Presentation\Controllers\LoginController;
use Modules\Shared\Presentation\Controllers\LogoutController;

/*
|--------------------------------------------------------------------------
| Public Routes — Guest Only
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthPageController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
    Route::get('/forgot-password', [AuthPageController::class, 'showForgotPassword'])->name('password.request');
    Route::get('/reset-password/{token}', [AuthPageController::class, 'showResetPassword'])->name('password.reset');

    // Login POST — rate limited: 5 attempts per minute per IP
    Route::post('/login', LoginController::class)
        ->name('login.post')
        ->middleware('throttle:5,1');
});

Route::get('/unauthorized', [AuthPageController::class, 'showUnauthorized'])->name('unauthorized');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', LogoutController::class)->name('logout');

    // PulseBar live data
    Route::get('/api/pulsebar/data', App\Http\Controllers\Api\PulseBarController::class)
        ->name('api.pulsebar.data')
        ->middleware('throttle:60,1');
    Route::post('/logout-all-devices', [LogoutController::class, 'logoutAllDevices'])->name('logout.all-devices');

    // ── Academic Module ──────────────────────────────────────────────────
    Route::prefix('academic')->name('academic.')->middleware('throttle:60,1')->group(function () {
        Route::get('/dashboard', AcademicDashboardController::class)->name('dashboard')
            ->middleware('role:student,advisor,admin');
        Route::get('/courses', ListCoursesController::class)->name('courses')
            ->middleware('role:student,advisor,admin,faculty');
        Route::get('/plan', AcademicPlanController::class)->name('plan')
            ->middleware('role:student,advisor,admin');
        Route::get('/profile', Modules\Academic\Presentation\Controllers\AcademicProfileController::class)->name('profile')
            ->middleware('role:student,advisor,admin');
        Route::get('/progress', Modules\Academic\Presentation\Controllers\AcademicProgressController::class)->name('progress')
            ->middleware('role:student,advisor,admin');
        Route::get('/alerts', GetStudentAlertsController::class)->name('alerts')
            ->middleware('role:student,advisor,admin');
        Route::get('/graduation-map', Modules\Academic\Presentation\Controllers\GraduationMapController::class)->name('graduation-map')
            ->middleware('role:student,advisor,admin');
        Route::get('/curriculum', ListCurriculumCoursesController::class)->name('curriculum')
            ->middleware('role:student,advisor,admin,faculty');
    });

    // ── Productivity Module ──────────────────────────────────────────────
    Route::prefix('productivity')->name('productivity.')->middleware('throttle:60,1')->group(function () {
        Route::get('/dashboard', ProductivityDashboardController::class)->name('dashboard')
            ->middleware('role:student');
        Route::get('/goals', [ProductivityGoalController::class, 'index'])->name('goals')
            ->middleware('role:student');
        Route::get('/goals/create', [ProductivityGoalController::class, 'index'])->name('goals.create')
            ->middleware('role:student');
        Route::get('/goals/{id}', [ProductivityGoalController::class, 'show'])->name('goals.show')
            ->middleware('role:student');
        Route::get('/tasks', [ProductivityTaskController::class, 'index'])->name('tasks')
            ->middleware('role:student');
        Route::get('/tasks/create', [ProductivityTaskController::class, 'index'])->name('tasks.create')
            ->middleware('role:student');
        Route::get('/tasks/{id}', [ProductivityTaskController::class, 'show'])->name('tasks.show')
            ->middleware('role:student');
        Route::post('/tasks/{id}/complete', [ProductivityTaskController::class, 'complete'])->name('tasks.complete')
            ->middleware(['role:student', 'throttle:30,1']);
        Route::get('/calendar', [ProductivityCalendarController::class, 'index'])->name('calendar')
            ->middleware('role:student');
        Route::get('/calendar/create', [ProductivityCalendarController::class, 'index'])->name('calendar.create')
            ->middleware('role:student');
        Route::get('/reminders', [ProductivityReminderController::class, 'index'])->name('reminders')
            ->middleware('role:student');
        Route::get('/reminders/create', [ProductivityReminderController::class, 'index'])->name('reminders.create')
            ->middleware('role:student');

        // Assignment routes
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index')
            ->middleware('role:student');
        Route::get('/assignments/{id}', [AssignmentController::class, 'show'])->name('assignments.show')
            ->middleware('role:student');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store')
            ->middleware(['role:student', 'throttle:30,1']);
        Route::post('/assignments/{id}/progress', [AssignmentController::class, 'updateProgress'])->name('assignments.update-progress')
            ->middleware(['role:student', 'throttle:30,1']);

        // Exam routes
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index')
            ->middleware('role:student');
        Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exams.show')
            ->middleware('role:student');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store')
            ->middleware(['role:student', 'throttle:30,1']);
        Route::post('/exams/{id}/status', [ExamController::class, 'updateStatus'])->name('exams.update-status')
            ->middleware(['role:student', 'throttle:30,1']);

        // Project routes
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index')
            ->middleware('role:student');
        Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show')
            ->middleware('role:student');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store')
            ->middleware(['role:student', 'throttle:30,1']);
        Route::post('/projects/{id}/progress', [ProjectController::class, 'updateProgress'])->name('projects.update-progress')
            ->middleware(['role:student', 'throttle:30,1']);
    });
});

/*
|--------------------------------------------------------------------------
| Home — Main Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home')->middleware('auth');
