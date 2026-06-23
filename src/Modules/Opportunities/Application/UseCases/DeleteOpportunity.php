<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

final readonly class DeleteOpportunity
{
    public function __construct(
        private OpportunityRepositoryInterface $repository,
    ) {}

    public function execute(string $id): void
    {
        $this->repository->delete(OpportunityId::of($id));
    }
}
