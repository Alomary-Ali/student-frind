<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class CourseCompleted
{
    public function __construct(
        public readonly string $enrollmentId,
        public readonly string $studentId,
        public readonly string $userId,
        public readonly string $courseId,
        public readonly string $grade,
        public readonly float $gradePoints,
        public readonly \DateTimeImmutable $completedAt,
    ) {}
}
