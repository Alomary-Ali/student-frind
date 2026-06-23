<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Events;

use DateTimeImmutable;

final readonly class ExperienceAdded
{
    public function __construct(
        public string $experienceId,
        public string $profileId,
        public string $company,
        public string $position,
        public DateTimeImmutable $occurredAt,
    ) {}
}
