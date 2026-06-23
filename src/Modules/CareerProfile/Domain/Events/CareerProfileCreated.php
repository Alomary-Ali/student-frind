<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Events;

use DateTimeImmutable;

final readonly class CareerProfileCreated
{
    public function __construct(
        public string $profileId,
        public string $studentId,
        public string $major,
        public DateTimeImmutable $occurredAt,
    ) {}
}
