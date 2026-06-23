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
            \Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentAssignmentRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Contracts\ExamRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentExamRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentProjectRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Contracts\GoalRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentGoalRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Contracts\TaskRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentTaskRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentReminderRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface::class,
            \Modules\Productivity\Infrastructure\Persistence\EloquentCalendarEventRepository::class,
        );

        $this->app->bind(
            \Modules\Productivity\Application\UseCases\CreateAssignment::class,
            fn ($app) => new \Modules\Productivity\Application\UseCases\CreateAssignment(
                $app->make(\Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Application\UseCases\UpdateAssignmentProgress::class,
            fn ($app) => new \Modules\Productivity\Application\UseCases\UpdateAssignmentProgress(
                $app->make(\Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Application\UseCases\CreateExam::class,
            fn ($app) => new \Modules\Productivity\Application\UseCases\CreateExam(
                $app->make(\Modules\Productivity\Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Application\UseCases\UpdateExamStatus::class,
            fn ($app) => new \Modules\Productivity\Application\UseCases\UpdateExamStatus(
                $app->make(\Modules\Productivity\Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Application\UseCases\CreateProject::class,
            fn ($app) => new \Modules\Productivity\Application\UseCases\CreateProject(
                $app->make(\Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Application\UseCases\UpdateProjectProgress::class,
            fn ($app) => new \Modules\Productivity\Application\UseCases\UpdateProjectProgress(
                $app->make(\Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Services\ProductivityScoreEngine::class,
            fn ($app) => new \Modules\Productivity\Domain\Services\ProductivityScoreEngine(
                $app->make(\Modules\Productivity\Domain\Contracts\TaskRepositoryInterface::class),
                $app->make(\Modules\Productivity\Domain\Contracts\GoalRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Services\PriorityEngine::class,
            fn ($app) => new \Modules\Productivity\Domain\Services\PriorityEngine(
                $app->make(\Modules\Productivity\Domain\Contracts\TaskRepositoryInterface::class),
                $app->make(\Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface::class),
                $app->make(\Modules\Productivity\Domain\Contracts\ExamRepositoryInterface::class),
            ),
        );

        $this->app->bind(
            \Modules\Productivity\Domain\Services\NotificationService::class,
            fn ($app) => new \Modules\Productivity\Domain\Services\NotificationService(
                $app->make(\Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface::class),
                $app->make(\Modules\Productivity\Domain\Contracts\TaskRepositoryInterface::class),
                $app->make(\Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface::class),
                $app->make(\Modules\Productivity\Domain\Contracts\ExamRepositoryInterface::class),
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
        $this->loadRoutesFrom(__DIR__.'/Presentation/Http/routes.php');
    }
}
