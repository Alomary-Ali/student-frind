<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Events;

use DateTimeImmutable;

final readonly class OpportunityUpdated
{
    public function __construct(
        public string $opportunityId,
        public string $type,
        public string $title,
        public DateTimeImmutable $occurredAt,
    ) {}
}
