<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class CreateCourseDto
{
    public function __construct(
        public string $code,
        public string $title,
        public string $description,
        public int $creditHours,
        public ?string $institutionId = null,
    ) {}
}
