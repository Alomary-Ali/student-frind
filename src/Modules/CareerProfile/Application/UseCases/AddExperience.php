<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use DateTimeImmutable;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final readonly class AddExperience
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private EventDispatcherInterface $events,
        private CareerProfileMapper $mapper,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     * @throws \RuntimeException
     * @throws ValidationException
     */
    public function execute(string $studentId, array $data): CareerProfileDto
    {
        $validated = Validator::make($data, [
            'company'     => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_current'  => 'boolean',
        ])->validate();

        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $isCurrent = (bool) ($validated['is_current'] ?? false);

        $profile->addExperience(
            id: ExperienceId::generate(),
            company: $validated['company'],
            position: $validated['position'],
            description: $validated['description'],
            startDate: new DateTimeImmutable($validated['start_date']),
            endDate: isset($validated['end_date']) ? new DateTimeImmutable($validated['end_date']) : null,
            isCurrent: $isCurrent,
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toCareerProfileDto($profile);
    }
}
