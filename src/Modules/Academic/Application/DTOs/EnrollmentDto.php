<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class EnrollmentDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $courseId,
        public string $semesterId,
        public string $status,
        public string $enrolledAt,
    ) {}
}
