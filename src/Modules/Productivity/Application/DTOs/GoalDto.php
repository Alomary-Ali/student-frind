<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class GoalDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $title,
        public string $description,
        public string $targetDate,
        public string $priority,
        public float $progress,
        public string $status,
        public string $goalType,
        public string $createdAt,
        public bool $isOverdue,
    ) {}
}
