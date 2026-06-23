<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ReadModels;

final readonly class StudentAcademicProfile
{
    public function __construct(
        public string $studentId,
        public string $userId,
        public string $studentNumber,
        public string $academicStatus,
        public string $academicStanding,
        public float $cumulativeGpa,
        public ?string $institutionId,
        public string $createdAt,
    ) {}
}
