<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class ServiceRequestDto
{
    public function __construct(
        public string $id,
        public string $refNumber,
        public string $categoryId,
        public string $studentId,
        public string $status,
        public string $priority,
        public ?string $notes,
        public ?string $adminNotes,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
