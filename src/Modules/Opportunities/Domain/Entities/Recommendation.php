<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Entities;

use DateTimeImmutable;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityScore;
use Modules\Opportunities\Domain\ValueObjects\RecommendationId;

final class Recommendation
{
    private function __construct(
        private readonly RecommendationId $id,
        private readonly string $studentId,
        private readonly OpportunityId $opportunityId,
        private OpportunityScore $score,
        private ?string $reason,
        private readonly DateTimeImmutable $generatedAt,
    ) {}

    public static function create(
        RecommendationId $id,
        string $studentId,
        OpportunityId $opportunityId,
        OpportunityScore $score,
        ?string $reason = null,
    ): self {
        return new self(
            $id,
            $studentId,
            $opportunityId,
            $score,
            $reason,
            new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        RecommendationId $id,
        string $studentId,
        OpportunityId $opportunityId,
        OpportunityScore $score,
        ?string $reason,
        DateTimeImmutable $generatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $opportunityId,
            $score,
            $reason,
            $generatedAt,
        );
    }

    public function id(): RecommendationId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function opportunityId(): OpportunityId
    {
        return $this->opportunityId;
    }

    public function score(): OpportunityScore
    {
        return $this->score;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    public function generatedAt(): DateTimeImmutable
    {
        return $this->generatedAt;
    }
}
