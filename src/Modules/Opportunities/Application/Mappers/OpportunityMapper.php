<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\Mappers;

use Modules\Opportunities\Application\DTOs\ApplicationDto;
use Modules\Opportunities\Application\DTOs\OpportunityDto;
use Modules\Opportunities\Application\DTOs\RecommendationDto;
use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Entities\OpportunityApplication;
use Modules\Opportunities\Domain\Entities\Recommendation;

final class OpportunityMapper
{
    public function toOpportunityDto(Opportunity $opportunity): OpportunityDto
    {
        return new OpportunityDto(
            id: $opportunity->id()->value(),
            title: $opportunity->title(),
            description: $opportunity->description(),
            provider: $opportunity->provider()->value,
            type: $opportunity->type()->value,
            location: $opportunity->location(),
            country: $opportunity->country(),
            deadline: $opportunity->deadline()?->format('Y-m-d H:i:s'),
            applyUrl: $opportunity->applyUrl(),
            status: $opportunity->status()->value,
            metadata: $opportunity->metadata(),
            sourceUrl: $opportunity->sourceUrl(),
            imageUrl: $opportunity->imageUrl(),
            tags: $opportunity->tags(),
            createdAt: $opportunity->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $opportunity->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toApplicationDto(OpportunityApplication $application): ApplicationDto
    {
        return new ApplicationDto(
            id: $application->id()->value(),
            opportunityId: $application->opportunityId()->value(),
            studentId: $application->studentId(),
            status: $application->status()->value,
            appliedAt: $application->appliedAt()?->format('Y-m-d H:i:s'),
            notes: $application->notes(),
        );
    }

    public function toRecommendationDto(Recommendation $recommendation): RecommendationDto
    {
        return new RecommendationDto(
            id: $recommendation->id()->value(),
            studentId: $recommendation->studentId(),
            opportunityId: $recommendation->opportunityId()->value(),
            score: $recommendation->score()->value(),
            reason: $recommendation->reason(),
            generatedAt: $recommendation->generatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
