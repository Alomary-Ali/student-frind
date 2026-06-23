<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use DateTimeImmutable;
use Modules\Opportunities\Application\DTOs\ApplicationDto;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Domain\Contracts\ApplicationRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Entities\OpportunityApplication;
use Modules\Opportunities\Domain\Events\OpportunityApplied;
use Modules\Opportunities\Domain\ValueObjects\ApplicationId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class ApplyToOpportunity
{
    public function __construct(
        private ApplicationRepositoryInterface $applications,
        private SavedOpportunityRepositoryInterface $saved,
        private EventDispatcherInterface $events,
        private OpportunityMapper $mapper,
    ) {}

    public function execute(string $studentId, string $opportunityId, ?string $notes = null): ApplicationDto
    {
        $oppId = OpportunityId::of($opportunityId);

        $existing = $this->applications->findByOpportunityAndStudent($oppId, $studentId);

        if ($existing !== null) {
            $existing->submit();
            $existing->updateNotes($notes ?? '');

            $this->applications->save($existing);

            return $this->mapper->toApplicationDto($existing);
        }

        $id = ApplicationId::generate();
        $application = OpportunityApplication::create(
            id: $id,
            opportunityId: $oppId,
            studentId: $studentId,
            notes: $notes,
        );

        $application->submit();

        $this->applications->save($application);

        if ($this->saved->isSaved($studentId, $oppId)) {
            $this->saved->delete($studentId, $oppId);
        }

        $this->events->dispatch([
            new OpportunityApplied(
                applicationId: $id->value(),
                opportunityId: $opportunityId,
                studentId: $studentId,
                status: 'applied',
                occurredAt: new DateTimeImmutable,
            ),
        ]);

        return $this->mapper->toApplicationDto($application);
    }
}
