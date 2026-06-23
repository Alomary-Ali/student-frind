<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class AssignAcademicPlanDto
{
    public function __construct(
        public string $studentId,
        public string $curriculumId,
        public string $actorUserId,
        public ?string $institutionId = null,
        public ?string $estimatedGraduationDate = null,
    ) {}
}
