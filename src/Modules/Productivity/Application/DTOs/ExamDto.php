<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class ExamDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $courseId,
        public string $title,
        public string $examType,
        public string $examDate,
        public string $location,
        public string $status,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
