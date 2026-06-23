<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class EnrollStudentDto
{
    public function __construct(
        public string $studentId,
        public string $courseId,
        public string $semesterId,
        public string $actorUserId,
    ) {}
}
