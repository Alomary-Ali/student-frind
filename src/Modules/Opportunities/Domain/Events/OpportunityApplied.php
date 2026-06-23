<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Events;

use DateTimeImmutable;

final readonly class OpportunityApplied
{
    public function __construct(
        public string $applicationId,
        public string $opportunityId,
        public string $studentId,
        public string $status,
        public DateTimeImmutable $occurredAt,
    ) {}
}
