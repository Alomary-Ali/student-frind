<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use Modules\Opportunities\Application\DTOs\RecommendationDto;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Domain\Contracts\RecommendationRepositoryInterface;

final readonly class GetRecommendedOpportunities
{
    public function __construct(
        private RecommendationRepositoryInterface $recommendations,
        private OpportunityMapper $mapper,
    ) {}

    /**
     * @return array<RecommendationDto>
     */
    public function execute(string $studentId, int $limit = 10): array
    {
        $recommendations = $this->recommendations->findTopByStudentId($studentId, $limit);

        return array_map(fn ($rec) => $this->mapper->toRecommendationDto($rec), $recommendations);
    }
}
