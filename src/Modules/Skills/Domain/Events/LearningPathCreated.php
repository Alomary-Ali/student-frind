<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Events;

use DateTimeImmutable;

final readonly class LearningPathCreated
{
    public function __construct(
        public string $learningPathId,
        public string $studentId,
        public string $title,
        public string $targetRole,
        public DateTimeImmutable $occurredAt,
    ) {}
}
