<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Contracts;

use Modules\Opportunities\Domain\Entities\Recommendation;
use Modules\Opportunities\Domain\ValueObjects\RecommendationId;

interface RecommendationRepositoryInterface
{
    /** @return array<Recommendation> */
    public function findByStudentId(string $studentId): array;

    /** @return array<Recommendation> */
    public function findTopByStudentId(string $studentId, int $limit = 10): array;

    public function findByOpportunityAndStudent(string $studentId, string $opportunityId): ?Recommendation;

    public function save(Recommendation $recommendation): void;

    public function delete(RecommendationId $id): void;
}
