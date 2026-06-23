<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class ProductivityDashboardDto
{
    public function __construct(
        public string $userId,
        public int $activeGoals,
        public int $completedGoals,
        public int $pendingTasks,
        public int $inProgressTasks,
        public int $completedTasks,
        public int $overdueTasks,
        public int $upcomingReminders,
        public float $overallCompletionRate,
        public array $recentTasks,
        public array $upcomingEvents,
    ) {}
}
