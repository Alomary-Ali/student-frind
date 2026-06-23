<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

final readonly class ProductivitySnapshotGenerated
{
    public function __construct(
        public string $snapshotId,
        public string $userId,
        public int $totalGoals,
        public int $completedGoals,
        public int $totalTasks,
        public int $completedTasks,
        public int $overdueTasks,
        public float $completionRate,
        public string $snapshotDate,
        public \DateTimeImmutable $occurredAt,
    ) {}
}
