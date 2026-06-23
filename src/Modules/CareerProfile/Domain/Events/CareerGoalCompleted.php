<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Events;

use DateTimeImmutable;

final readonly class CareerGoalCompleted
{
    public function __construct(
        public string $goalId,
        public string $profileId,
        public string $title,
        public DateTimeImmutable $occurredAt,
    ) {}
}
