<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateCareerProfile
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private EventDispatcherInterface $events,
        private CareerProfileMapper $mapper,
    ) {}

    public function execute(string $studentId, string $major, string $summary = ''): CareerProfileDto
    {
        $profile = CareerProfile::create(
            id: CareerProfileId::generate(),
            studentId: StudentId::of($studentId),
            major: $major,
            summary: $summary,
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toCareerProfileDto($profile);
    }
}
