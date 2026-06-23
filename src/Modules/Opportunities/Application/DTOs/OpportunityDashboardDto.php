<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\DTOs;

final readonly class OpportunityDashboardDto
{
    /**
     * @param  array<RecommendationDto>  $recommendations
     * @param  array<OpportunityDto>  $saved
     * @param  array<ApplicationDto>  $applications
     * @param  array<OpportunityDto>  $recentOpportunities
     */
    public function __construct(
        public array $recommendations,
        public array $saved,
        public array $applications,
        public array $recentOpportunities,
        public int $totalCount,
        public int $savedCount,
        public int $appliedCount,
        public int $recommendationCount,
    ) {}
}
