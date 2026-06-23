<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class OptimizeLinkedInDto
{
    public function __construct(
        public string $studentId,
    ) {}
}
