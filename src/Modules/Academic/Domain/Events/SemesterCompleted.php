<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class SemesterCompleted
{
    public function __construct(
        public readonly string $studentId,
        public readonly string $semesterId,
        public readonly float $semesterGpa,
        public readonly \DateTimeImmutable $completedAt,
    ) {}
}
