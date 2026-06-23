<?php
declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class ServiceRequestReviewed
{
    public function __construct(
        public string $serviceRequestId,
        public string $studentId,
        public string $reviewerId,
        public string $status,
        public string $notes,
        public DateTimeImmutable $occurredAt,
    ) {}
}
