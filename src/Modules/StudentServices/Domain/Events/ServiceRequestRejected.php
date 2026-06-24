<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class ServiceRequestRejected
{
    public function __construct(
        public string $serviceRequestId,
        public string $studentId,
        public string $reviewerId,
        public string $reason,
        public DateTimeImmutable $occurredAt,
    ) {}
}
