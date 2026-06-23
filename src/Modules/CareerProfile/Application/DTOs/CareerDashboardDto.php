<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class CareerDashboardDto
{
    /**
     * @param  array<PortfolioItemDto>  $portfolioItems
     * @param  array<ExperienceDto>  $experiences
     * @param  array<CareerGoalDto>  $careerGoals
     */
    public function __construct(
        public ?CareerProfileDto $profile,
        public int $careerScore,
        public int $linkedInScore,
        public array $portfolioItems,
        public array $experiences,
        public array $careerGoals,
        public int $skillCount,
        public int $certificationCount,
    ) {}
}
