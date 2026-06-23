<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Events;

use DateTimeImmutable;

final readonly class RecommendationGenerated
{
    public function __construct(
        public string $recommendationId,
        public string $studentId,
        public string $opportunityId,
        public float $score,
        public DateTimeImmutable $occurredAt,
    ) {}
}
