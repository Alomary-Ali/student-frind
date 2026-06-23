<?php
declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class DocumentRequested
{
    public function __construct(
        public string $documentRequestId,
        public string $studentId,
        public string $documentType,
        public DateTimeImmutable $occurredAt,
    ) {}
}
