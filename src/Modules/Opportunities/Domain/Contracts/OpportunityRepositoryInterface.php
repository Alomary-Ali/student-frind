<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Contracts;

use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

interface OpportunityRepositoryInterface
{
    public function findById(OpportunityId $id): ?Opportunity;

    /** @return array<Opportunity> */
    public function findByType(OpportunityType $type): array;

    /** @return array<Opportunity> */
    public function findAll(): array;

    /** @return array<Opportunity> */
    public function search(string $query): array;

    public function save(Opportunity $opportunity): void;

    public function delete(OpportunityId $id): void;
}
