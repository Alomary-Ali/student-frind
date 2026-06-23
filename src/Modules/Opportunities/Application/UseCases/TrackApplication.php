<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\UseCases;

use Modules\Opportunities\Application\DTOs\ApplicationDto;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Domain\Contracts\ApplicationRepositoryInterface;
use Modules\Opportunities\Domain\Enums\ApplicationStatus;
use Modules\Opportunities\Domain\ValueObjects\ApplicationId;

final readonly class TrackApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applications,
        private OpportunityMapper $mapper,
    ) {}

    public function execute(string $applicationId, string $status): ApplicationDto
    {
        $id = ApplicationId::of($applicationId);
        $application = $this->applications->findById($id);

        if ($application === null) {
            throw new \RuntimeException("Application not found with id {$applicationId}");
        }

        $application->updateStatus(ApplicationStatus::from($status));

        $this->applications->save($application);

        return $this->mapper->toApplicationDto($application);
    }
}
