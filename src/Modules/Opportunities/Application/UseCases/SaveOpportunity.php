<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Entities\SavedOpportunity;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

final readonly class SaveOpportunity
{
    public function __construct(
        private SavedOpportunityRepositoryInterface $repository,
    ) {}

    public function execute(string $studentId, string $opportunityId): bool
    {
        $oppId = OpportunityId::of($opportunityId);

        if ($this->repository->isSaved($studentId, $oppId)) {
            $this->repository->delete($studentId, $oppId);

            return false;
        }

        $saved = SavedOpportunity::create(
            studentId: $studentId,
            opportunityId: $oppId,
        );

        $this->repository->save($saved);

        return true;
    }
}
