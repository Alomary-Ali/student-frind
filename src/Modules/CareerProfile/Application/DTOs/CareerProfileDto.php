<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class CareerProfileDto
{
    /**
     * @param  array<PortfolioItemDto>  $portfolioItems
     * @param  array<ExperienceDto>  $experiences
     * @param  array<ResumeDto>  $resumes
     * @param  array<CareerGoalDto>  $careerGoals
     */
    public function __construct(
        public string $id,
        public string $studentId,
        public string $major,
        public string $summary,
        public array $interests,
        public array $languages,
        public array $portfolioItems,
        public array $experiences,
        public array $resumes,
        public array $careerGoals,
    ) {}
}
