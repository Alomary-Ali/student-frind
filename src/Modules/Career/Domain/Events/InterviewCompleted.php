<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Events;

use DateTimeImmutable;

final readonly class InterviewCompleted
{
    public function __construct(
        public string $interviewId,
        public string $studentId,
        public int $score,
        public ?string $feedback,
        public DateTimeImmutable $occurredAt,
    ) {}
}
