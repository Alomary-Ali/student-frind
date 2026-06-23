<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class AssignmentDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $courseId,
        public string $title,
        public string $description,
        public string $assignedAt,
        public string $dueDate,
        public string $status,
        public ?string $grade,
        public ?string $submissionUrl,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
