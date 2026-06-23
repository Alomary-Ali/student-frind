<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class StudentEnrolled
{
    public function __construct(
        public readonly string $enrollmentId,
        public readonly string $studentId,
        public readonly string $userId,
        public readonly string $courseId,
        public readonly string $semesterId,
        public readonly \DateTimeImmutable $enrolledAt,
    ) {}
}
