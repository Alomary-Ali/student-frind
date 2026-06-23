<?php

declare(strict_types=1);

namespace Modules\Productivity;

use Illuminate\Support\ServiceProvider;

/**
 * ProductivityServiceProvider
 *
 * Bootstraps the Productivity module.
 * Responsible for: Tasks, goals, habits, scheduling, time management
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class ProductivityServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        $this->app->bind(
            Domain\Contracts\AssignmentRepositoryInterface::class,
            Infrastructure\Persistence\EloquentAssignmentRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\ExamRepositoryInterface::class,
            Infrastructure\Persistence\EloquentExamRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\ProjectRepositoryInterface::class,
            Infrastructure\Persistence\EloquentProjectRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\GoalRepositoryInterface::class,
            Infrastructure\Persistence\EloquentGoalRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\TaskRepositoryInterface::class,
            Infrastructure\Persistence\EloquentTaskRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\ReminderRepositoryInterface::class,
            Infrastructure\Persistence\EloquentReminderRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\CalendarEventRepositoryInterface::class,
            Infrastructure\Persistence\EloquentCalendarEventRepository::class,
        );

        $this->app->bind(
            Application\UseCases\CreateAssignment::class,
            fn ($app) => new Application\UseCases\CreateAssignment(
                $app->make(Domain\Contracts\AssignmentRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Application\UseCases\UpdateAssignmentProgress::class,
            fn ($app) => new Application\UseCases\UpdateAssignmentProgress(
                $app->make(Domain\Contracts\AssignmentRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Application\UseCases\CreateExam::class,
            fn ($app) => new Application\UseCases\CreateExam(
                $app->make(Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Application\UseCases\UpdateExamStatus::class,
            fn ($app) => new Application\UseCases\UpdateExamStatus(
                $app->make(Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Application\UseCases\CreateProject::class,
            fn ($app) => new Application\UseCases\CreateProject(
                $app->make(Domain\Contracts\ProjectRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Application\UseCases\UpdateProjectProgress::class,
            fn ($app) => new Application\UseCases\UpdateProjectProgress(
                $app->make(Domain\Contracts\ProjectRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Domain\Services\ProductivityScoreEngine::class,
            fn ($app) => new Domain\Services\ProductivityScoreEngine(
                $app->make(Domain\Contracts\TaskRepositoryInterface::class),
                $app->make(Domain\Contracts\GoalRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Domain\Services\PriorityEngine::class,
            fn ($app) => new Domain\Services\PriorityEngine(
                $app->make(Domain\Contracts\TaskRepositoryInterface::class),
                $app->make(Domain\Contracts\AssignmentRepositoryInterface::class),
                $app->make(Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            Domain\Services\NotificationService::class,
            fn ($app) => new Domain\Services\NotificationService(
                $app->make(Domain\Contracts\ReminderRepositoryInterface::class),
                $app->make(Domain\Contracts\TaskRepositoryInterface::class),
                $app->make(Domain\Contracts\AssignmentRepositoryInterface::class),
                $app->make(Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );
    }

    /**
     * Bootstrap module services.
     * Load routes, migrations, event listeners, and policies.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(database_path('migrations'));
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Http/routes.php');
    }
}
