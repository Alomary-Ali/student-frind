<?php

declare(strict_types=1);

namespace Modules\Productivity\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CreateCalendarEvent;
use Modules\Productivity\Application\UseCases\CreateGoal;
use Modules\Productivity\Application\UseCases\CreateReminder;
use Modules\Productivity\Application\UseCases\CreateTask;
use Modules\Productivity\Application\UseCases\CompleteTask;
use Modules\Productivity\Application\UseCases\GenerateProductivitySnapshot;
use Modules\Productivity\Application\UseCases\GetProductivityDashboard;
use Modules\Productivity\Application\UseCases\UpdateGoalProgress;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ProductivitySnapshotRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Infrastructure\Persistence\EloquentCalendarEventRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentGoalRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentProductivitySnapshotRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentReminderRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentTaskRepository;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final class ProductivityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->bind(GoalRepositoryInterface::class, EloquentGoalRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
        $this->app->bind(ReminderRepositoryInterface::class, EloquentReminderRepository::class);
        $this->app->bind(CalendarEventRepositoryInterface::class, EloquentCalendarEventRepository::class);
        $this->app->bind(ProductivitySnapshotRepositoryInterface::class, EloquentProductivitySnapshotRepository::class);

        $this->app->singleton(ProductivityMapper::class);

        $this->app->bind(CreateGoal::class, function ($app) {
            return new CreateGoal(
                $app->make(GoalRepositoryInterface::class),
                $app->make(EventDispatcherInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(UpdateGoalProgress::class, function ($app) {
            return new UpdateGoalProgress(
                $app->make(GoalRepositoryInterface::class),
                $app->make(EventDispatcherInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(CreateTask::class, function ($app) {
            return new CreateTask(
                $app->make(TaskRepositoryInterface::class),
                $app->make(GoalRepositoryInterface::class),
                $app->make(EventDispatcherInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(CompleteTask::class, function ($app) {
            return new CompleteTask(
                $app->make(TaskRepositoryInterface::class),
                $app->make(EventDispatcherInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(CreateReminder::class, function ($app) {
            return new CreateReminder(
                $app->make(ReminderRepositoryInterface::class),
                $app->make(TaskRepositoryInterface::class),
                $app->make(EventDispatcherInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(CreateCalendarEvent::class, function ($app) {
            return new CreateCalendarEvent(
                $app->make(CalendarEventRepositoryInterface::class),
                $app->make(TaskRepositoryInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(GenerateProductivitySnapshot::class, function ($app) {
            return new GenerateProductivitySnapshot(
                $app->make(GoalRepositoryInterface::class),
                $app->make(TaskRepositoryInterface::class),
                $app->make(ProductivitySnapshotRepositoryInterface::class),
                $app->make(EventDispatcherInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });

        $this->app->bind(GetProductivityDashboard::class, function ($app) {
            return new GetProductivityDashboard(
                $app->make(GoalRepositoryInterface::class),
                $app->make(TaskRepositoryInterface::class),
                $app->make(ReminderRepositoryInterface::class),
                $app->make(CalendarEventRepositoryInterface::class),
                $app->make(ProductivityMapper::class),
            );
        });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Http/routes.php');
    }
}
