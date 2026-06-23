<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Events;

use DateTimeImmutable;

final readonly class InterviewScheduled
{
    public function __construct(
        public string $interviewId,
        public string $studentId,
        public string $type,
        public DateTimeImmutable $scheduledAt,
        public DateTimeImmutable $occurredAt,
    ) {}
}
