<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class CareerGoalDto
{
    public function __construct(
        public string $id,
        public string $careerProfileId,
        public string $title,
        public string $targetDate,
        public string $status,
        public int $progress,
    ) {}
}
