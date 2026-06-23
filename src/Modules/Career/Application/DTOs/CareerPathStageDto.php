<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class CareerPathStageDto
{
    public function __construct(
        public string $id,
        public string $title,
        public int $order = 0,
        public array $requiredSkills = [],
        public int $durationMonths = 0,
        public ?string $salaryRange = null,
        public ?string $description = null,
    ) {}
}
