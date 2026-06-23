<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use DateTimeImmutable;
use Modules\Opportunities\Application\DTOs\RecommendationDto;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\RecommendationRepositoryInterface;
use Modules\Opportunities\Domain\Entities\Recommendation;
use Modules\Opportunities\Domain\Events\RecommendationGenerated;
use Modules\Opportunities\Domain\Services\RecommendationEngine;
use Modules\Opportunities\Domain\ValueObjects\RecommendationId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class GenerateRecommendations
{
    public function __construct(
        private RecommendationEngine $engine,
        private RecommendationRepositoryInterface $recommendations,
        private OpportunityRepositoryInterface $opportunities,
        private EventDispatcherInterface $events,
        private OpportunityMapper $mapper,
    ) {}

    /**
     * @return array<RecommendationDto>
     */
    public function execute(string $studentId): array
    {
        $allOpportunities = $this->opportunities->findAll();
        $scored = $this->engine->rank($studentId, $allOpportunities);

        $dtos = [];
        foreach ($scored as $result) {
            $id = RecommendationId::generate();

            $existing = $this->recommendations->findByOpportunityAndStudent($studentId, $result['opportunity']->id()->value());

            if ($existing !== null) {
                $dtos[] = $this->mapper->toRecommendationDto($existing);

                continue;
            }

            $recommendation = Recommendation::create(
                id: $id,
                studentId: $studentId,
                opportunityId: $result['opportunity']->id(),
                score: $result['score'],
                reason: $result['reason'],
            );

            $this->recommendations->save($recommendation);

            $this->events->dispatch([
                new RecommendationGenerated(
                    recommendationId: $id->value(),
                    studentId: $studentId,
                    opportunityId: $result['opportunity']->id()->value(),
                    score: $result['score']->value(),
                    occurredAt: new DateTimeImmutable,
                ),
            ]);

            $dto = $this->mapper->toRecommendationDto($recommendation);
            $dto->setOpportunity($this->mapper->toOpportunityDto($result['opportunity']));
            $dtos[] = $dto;
        }

        return $dtos;
    }
}
