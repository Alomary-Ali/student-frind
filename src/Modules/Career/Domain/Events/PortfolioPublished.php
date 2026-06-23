<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Events;

use DateTimeImmutable;

final readonly class PortfolioPublished
{
    public function __construct(
        public string $portfolioId,
        public string $studentId,
        public string $slug,
        public DateTimeImmutable $occurredAt,
    ) {}
}
