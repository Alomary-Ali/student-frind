<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class CareerPathDto
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description = null,
        public string $targetRole = '',
        public array $requiredSkills = [],
        public array $stages = [],
        public ?string $averageSalary = null,
        public ?string $growthRate = null,
        public int $totalDuration = 0,
    ) {}
}
