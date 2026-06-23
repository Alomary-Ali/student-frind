<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\DTOs;

final class RecommendationDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $opportunityId,
        public float $score,
        public ?string $reason,
        public string $generatedAt,
        public ?OpportunityDto $opportunity = null,
    ) {}

    public function setOpportunity(OpportunityDto $opportunity): void
    {
        $this->opportunity = $opportunity;
    }
}
