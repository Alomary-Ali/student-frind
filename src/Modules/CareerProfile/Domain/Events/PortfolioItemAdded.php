<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Events;

use DateTimeImmutable;

final readonly class PortfolioItemAdded
{
    public function __construct(
        public string $itemId,
        public string $profileId,
        public string $title,
        public DateTimeImmutable $occurredAt,
    ) {}
}
