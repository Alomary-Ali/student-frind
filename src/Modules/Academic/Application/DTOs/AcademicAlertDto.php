<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class AcademicAlertDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $alertType,
        public string $severity,
        public string $message,
        public ?array $metadata,
        public bool $isResolved,
        public string $createdAt,
        public ?string $resolvedAt,
        public ?string $resolvedBy,
    ) {}
}
