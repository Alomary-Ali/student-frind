<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class ProjectDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $title,
        public string $description,
        public string $startDate,
        public string $dueDate,
        public string $status,
        public int $progressPercentage,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
