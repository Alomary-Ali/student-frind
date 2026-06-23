<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use DateTimeImmutable;
use Modules\Opportunities\Application\DTOs\OpportunityDto;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\Enums\Provider;
use Modules\Opportunities\Domain\Events\OpportunityCreated;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateOpportunity
{
    public function __construct(
        private OpportunityRepositoryInterface $repository,
        private EventDispatcherInterface $events,
        private OpportunityMapper $mapper,
    ) {}

    public function execute(
        string $type,
        string $title,
        string $description,
        string $provider,
        ?string $location = null,
        ?string $country = null,
        ?string $deadline = null,
        ?string $applyUrl = null,
        array $metadata = [],
        array $tags = [],
    ): OpportunityDto {
        $id = OpportunityId::generate();
        $providerEnum = Provider::tryFrom($provider) ?? Provider::OTHER;
        $typeEnum = OpportunityType::from($type);

        $opportunity = Opportunity::reconstitute(
            id: $id,
            title: $title,
            description: $description,
            provider: $providerEnum,
            type: $typeEnum,
            location: $location,
            country: $country,
            deadline: $deadline ? new DateTimeImmutable($deadline) : null,
            applyUrl: $applyUrl,
            status: \Modules\Opportunities\Domain\Enums\OpportunityStatus::ACTIVE,
            metadata: $metadata,
            sourceUrl: null,
            imageUrl: null,
            tags: $tags,
            createdAt: new DateTimeImmutable,
            updatedAt: new DateTimeImmutable,
        );

        $this->repository->save($opportunity);

        $now = new DateTimeImmutable;
        $this->events->dispatch([
            new OpportunityCreated(
                opportunityId: $id->value(),
                type: $type,
                title: $title,
                provider: $provider,
                occurredAt: $now,
            ),
        ]);

        return $this->mapper->toOpportunityDto($opportunity);
    }
}
