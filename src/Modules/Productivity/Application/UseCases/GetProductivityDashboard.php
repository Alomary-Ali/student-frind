<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\ProductivityDashboardDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;

final readonly class GetProductivityDashboard
{
    public function __construct(
        private GoalRepositoryInterface $goals,
        private TaskRepositoryInterface $tasks,
        private ReminderRepositoryInterface $reminders,
        private CalendarEventRepositoryInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(string $userId): ProductivityDashboardDto
    {
        $allGoals = $this->goals->findByUserId($userId);
        $allTasks = $this->tasks->findByUserId($userId);
        $allReminders = $this->reminders->findByUserId($userId);
        $allEvents = $this->events->findByUserId($userId);

        $activeGoals = count(array_filter($allGoals, fn ($g) => $g->status()->isActive()));
        $completedGoals = count(array_filter($allGoals, fn ($g) => $g->status()->isCompleted()));

        $pendingTasks = count(array_filter($allTasks, fn ($t) => $t->status()->isPending()));
        $inProgressTasks = count(array_filter($allTasks, fn ($t) => $t->status()->isInProgress()));
        $completedTasks = count(array_filter($allTasks, fn ($t) => $t->status()->isCompleted()));
        $overdueTasks = count(array_filter($allTasks, fn ($t) => $t->isOverdue()));

        $upcomingReminders = count(array_filter($allReminders, fn ($r) => $r->isDue()));

        $totalTasks = count($allTasks);
        $overallCompletionRate = $totalTasks > 0
            ? ($completedTasks / $totalTasks) * 100
            : 0.0;

        $recentTasks = array_slice(
            $this->mapper->toTaskDtoList($allTasks),
            0,
            5,
        );

        $upcomingEvents = array_slice(
            $this->mapper->toCalendarEventDtoList(
                array_filter($allEvents, fn ($e) => $e->isFuture()),
            ),
            0,
            5,
        );

        return new ProductivityDashboardDto(
            userId: $userId,
            activeGoals: $activeGoals,
            completedGoals: $completedGoals,
            pendingTasks: $pendingTasks,
            inProgressTasks: $inProgressTasks,
            completedTasks: $completedTasks,
            overdueTasks: $overdueTasks,
            upcomingReminders: $upcomingReminders,
            overallCompletionRate: $overallCompletionRate,
            recentTasks: $recentTasks,
            upcomingEvents: $upcomingEvents,
        );
    }
}
