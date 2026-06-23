<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class CreateTaskDto
{
    public function __construct(
        public string $userId,
        public string $title,
        public string $description,
        public ?string $dueDate,
        public string $priority,
        public ?string $linkedGoalId,
    ) {}
}
