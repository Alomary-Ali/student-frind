<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class TaskDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $title,
        public string $description,
        public ?string $dueDate,
        public string $priority,
        public string $status,
        public ?string $linkedGoalId,
        public string $createdAt,
        public ?string $completedAt,
        public bool $isOverdue,
    ) {}
}
