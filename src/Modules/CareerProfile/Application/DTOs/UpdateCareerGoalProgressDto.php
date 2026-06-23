<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class UpdateCareerGoalProgressDto
{
    public function __construct(
        public string $studentId,
        public string $goalId,
        public int $progress,
    ) {}
}
