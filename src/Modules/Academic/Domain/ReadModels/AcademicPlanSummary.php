<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ReadModels;

final readonly class AcademicPlanSummary
{
    public function __construct(
        public string $academicPlanId,
        public string $studentId,
        public string $curriculumId,
        public string $curriculumName,
        public string $status,
        public string $assignedAt,
    ) {}
}
