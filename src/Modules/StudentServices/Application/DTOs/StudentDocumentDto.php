<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class StudentDocumentDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $type,
        public string $title,
        public ?string $filePath,
        public string $status,
        public ?string $verificationCode,
        public array $metadata,
        public string $createdAt,
    ) {}
}
