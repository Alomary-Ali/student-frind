<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class ExperienceDto
{
    public function __construct(
        public string $id,
        public string $careerProfileId,
        public string $company,
        public string $position,
        public string $description,
        public string $startDate,
        public ?string $endDate,
        public bool $isCurrent,
    ) {}
}
