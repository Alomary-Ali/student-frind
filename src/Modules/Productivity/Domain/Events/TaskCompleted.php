<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

final readonly class TaskCompleted
{
    public function __construct(
        public string $taskId,
        public string $userId,
        public string $title,
        public ?string $linkedGoalId,
        public \DateTimeImmutable $completedAt,
    ) {}
}
