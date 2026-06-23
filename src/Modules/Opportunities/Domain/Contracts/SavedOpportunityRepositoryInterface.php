<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Contracts;

use Modules\Opportunities\Domain\Entities\SavedOpportunity;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

interface SavedOpportunityRepositoryInterface
{
    /** @return array<SavedOpportunity> */
    public function findByStudentId(string $studentId): array;

    public function findByOpportunityAndStudent(OpportunityId $opportunityId, string $studentId): ?SavedOpportunity;

    public function isSaved(string $studentId, OpportunityId $opportunityId): bool;

    public function save(SavedOpportunity $saved): void;

    public function delete(string $studentId, OpportunityId $opportunityId): void;
}
