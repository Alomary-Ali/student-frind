<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

final readonly class GoalCompleted
{
    public function __construct(
        public string $goalId,
        public string $userId,
        public string $title,
        public \DateTimeImmutable $completedAt,
    ) {}
}
