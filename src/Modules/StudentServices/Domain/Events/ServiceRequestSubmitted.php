<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class ServiceRequestSubmitted
{
    public function __construct(
        public string $serviceRequestId,
        public string $studentId,
        public string $categoryId,
        public string $status,
        public DateTimeImmutable $occurredAt,
    ) {}
}
