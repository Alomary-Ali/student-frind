<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use DateTimeImmutable;
use Modules\Opportunities\Application\DTOs\OpportunityDto;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class UpdateOpportunity
{
    public function __construct(
        private OpportunityRepositoryInterface $repository,
        private EventDispatcherInterface $events,
        private OpportunityMapper $mapper,
    ) {}

    public function execute(
        string $id,
        string $title,
        string $description,
        ?string $location = null,
        ?string $country = null,
        ?string $deadline = null,
        ?string $applyUrl = null,
        array $metadata = [],
    ): OpportunityDto {
        $opportunityId = OpportunityId::of($id);
        $opportunity = $this->repository->findById($opportunityId);

        if ($opportunity === null) {
            throw new \RuntimeException("Opportunity not found with id {$id}");
        }

        $opportunity->updateDetails(
            title: $title,
            description: $description,
            location: $location,
            country: $country,
            deadline: $deadline ? new DateTimeImmutable($deadline) : null,
            applyUrl: $applyUrl,
            metadata: $metadata,
        );

        $this->repository->save($opportunity);

        return $this->mapper->toOpportunityDto($opportunity);
    }
}
