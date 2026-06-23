<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Events;

use DateTimeImmutable;

final readonly class ResumeGenerated
{
    public function __construct(
        public string $resumeId,
        public string $profileId,
        public string $template,
        public DateTimeImmutable $occurredAt,
    ) {}
}
