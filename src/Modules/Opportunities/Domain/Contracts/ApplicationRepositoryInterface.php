<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Contracts;

use Modules\Opportunities\Domain\Entities\OpportunityApplication;
use Modules\Opportunities\Domain\ValueObjects\ApplicationId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

interface ApplicationRepositoryInterface
{
    public function findById(ApplicationId $id): ?OpportunityApplication;

    /** @return array<OpportunityApplication> */
    public function findByStudentId(string $studentId): array;

    public function findByOpportunityAndStudent(OpportunityId $opportunityId, string $studentId): ?OpportunityApplication;

    public function save(OpportunityApplication $application): void;

    public function delete(ApplicationId $id): void;
}
