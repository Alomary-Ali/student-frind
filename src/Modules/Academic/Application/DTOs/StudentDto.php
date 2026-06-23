<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class StudentDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $studentNumber,
        public string $academicStatus,
        public string $academicStanding,
        public float $cumulativeGpa,
        public ?float $semesterGpa,
        public ?string $currentSemesterId,
        public ?string $institutionId,
        public ?string $universityId,
        public ?string $collegeId,
        public ?string $departmentId,
        public ?string $majorId,
        public string $level,
        public string $createdAt,
    ) {}
}
