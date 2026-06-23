<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

final readonly class TaskCreated
{
    public function __construct(
        public string $taskId,
        public string $userId,
        public string $title,
        public ?string $dueDate,
        public string $priority,
        public ?string $linkedGoalId,
        public \DateTimeImmutable $occurredAt,
    ) {}
}
