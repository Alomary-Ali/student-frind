<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class PortfolioItemDto
{
    public function __construct(
        public string $id,
        public string $careerProfileId,
        public string $title,
        public string $description,
        public ?string $projectUrl,
        public ?string $githubUrl,
        public string $startDate,
        public ?string $endDate,
        public array $technologies,
    ) {}
}
