<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class AcademicPlanAssigned
{
    public function __construct(
        public readonly string $academicPlanId,
        public readonly string $studentId,
        public readonly string $curriculumId,
        public readonly \DateTimeImmutable $assignedAt,
    ) {}
}
