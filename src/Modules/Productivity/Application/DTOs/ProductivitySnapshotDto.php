<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class ProductivitySnapshotDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public int $totalGoals,
        public int $completedGoals,
        public int $totalTasks,
        public int $completedTasks,
        public int $overdueTasks,
        public float $completionRate,
        public string $snapshotDate,
        public string $createdAt,
    ) {}
}
