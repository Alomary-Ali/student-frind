<?php

declare(strict_types=1);

namespace Modules\Academic\Application\DTOs;

final readonly class CourseDto
{
    public function __construct(
        public string $id,
        public string $code,
        public string $title,
        public string $description,
        public int $creditHours,
        public bool $isActive,
        public ?string $institutionId,
    ) {}
}
