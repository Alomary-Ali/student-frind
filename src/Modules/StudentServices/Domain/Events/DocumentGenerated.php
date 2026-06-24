<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class DocumentGenerated
{
    public function __construct(
        public string $documentId,
        public string $studentId,
        public string $documentType,
        public string $filePath,
        public string $verificationCode,
        public DateTimeImmutable $occurredAt,
    ) {}
}
