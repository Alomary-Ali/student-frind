<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Gateways;

use Modules\Career\Domain\Contracts\Gateways\OpportunitiesGatewayInterface;
use Modules\Opportunities\Domain\Contracts\ApplicationRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\RecommendationRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;

final class OpportunitiesGateway implements OpportunitiesGatewayInterface
{
    public function __construct(
        private readonly SavedOpportunityRepositoryInterface $savedRepo,
        private readonly ApplicationRepositoryInterface $applicationRepo,
        private readonly RecommendationRepositoryInterface $recommendationRepo,
    ) {}

    public function getSavedOpportunities(string $studentId): array
    {
        $saved = $this->savedRepo->findByStudentId($studentId);

        return array_map(fn ($s) => [
            'id' => $s->id()->value(),
            'opportunity_id' => $s->opportunityId()->value(),
            'saved_at' => $s->savedAt()->format('Y-m-d H:i:s'),
        ], $saved);
    }

    public function getApplications(string $studentId): array
    {
        $applications = $this->applicationRepo->findByStudentId($studentId);

        return array_map(fn ($a) => [
            'id' => $a->id()->value(),
            'opportunity_id' => $a->opportunityId()->value(),
            'status' => $a->status()->value,
            'notes' => $a->notes(),
            'applied_at' => $a->appliedAt()->format('Y-m-d H:i:s'),
        ], $applications);
    }

    public function getRecommendations(string $studentId): array
    {
        $recommendations = $this->recommendationRepo->findByStudentId($studentId);

        return array_map(fn ($r) => [
            'id' => $r->id()->value(),
            'opportunity_id' => $r->opportunityId()->value(),
            'score' => $r->score()->value(),
            'reason' => $r->reason(),
            'generated_at' => $r->generatedAt()->format('Y-m-d H:i:s'),
        ], $recommendations);
    }

    public function getRecommendedOpportunities(string $studentId, int $limit = 10): array
    {
        $recommendations = $this->recommendationRepo->findTopByStudentId($studentId, $limit);

        return array_map(fn ($r) => [
            'id' => $r->id()->value(),
            'opportunity_id' => $r->opportunityId()->value(),
            'score' => $r->score()->value(),
            'reason' => $r->reason(),
        ], $recommendations);
    }
}
