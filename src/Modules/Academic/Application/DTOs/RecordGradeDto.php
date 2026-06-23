<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class RecordGradeDto
{
    public function __construct(
        public string $enrollmentId,
        public string $gradeLetter,
        public string $recordedByUserId,
    ) {}
}
