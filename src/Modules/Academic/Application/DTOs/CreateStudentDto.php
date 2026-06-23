<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class CreateStudentDto
{
    public function __construct(
        public string $userId,
        public string $studentNumber,
        public ?string $institutionId = null,
        public ?string $universityId = null,
        public ?string $collegeId = null,
        public ?string $departmentId = null,
        public ?string $majorId = null,
        public string $level = '1',
        public ?float $semesterGpa = null,
        public ?string $currentSemesterId = null,
    ) {}
}
