<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class DocumentRequestDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $documentType,
        public string $status,
        public ?string $notes,
        public string $createdAt,
    ) {}
}
