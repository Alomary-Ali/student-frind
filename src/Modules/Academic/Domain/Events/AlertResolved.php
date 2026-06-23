<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

use DateTimeImmutable;

final readonly class AlertResolved
{
    public function __construct(
        public string $alertId,
        public string $studentId,
        public string $resolvedBy,
        public DateTimeImmutable $resolvedAt,
    ) {}
}
