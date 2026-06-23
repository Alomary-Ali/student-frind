<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Entities;

use DateTimeImmutable;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

final class SavedOpportunity
{
    private function __construct(
        private readonly string $studentId,
        private readonly OpportunityId $opportunityId,
        private readonly DateTimeImmutable $savedAt,
    ) {}

    public static function create(
        string $studentId,
        OpportunityId $opportunityId,
    ): self {
        return new self(
            $studentId,
            $opportunityId,
            new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        string $studentId,
        OpportunityId $opportunityId,
        DateTimeImmutable $savedAt,
    ): self {
        return new self(
            $studentId,
            $opportunityId,
            $savedAt,
        );
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function opportunityId(): OpportunityId
    {
        return $this->opportunityId;
    }

    public function savedAt(): DateTimeImmutable
    {
        return $this->savedAt;
    }
}
