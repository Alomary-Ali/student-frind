<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use DateTimeImmutable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateCareerGoal
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private EventDispatcherInterface $events,
        private CareerProfileMapper $mapper,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     *
     * @throws \RuntimeException
     * @throws ValidationException
     */
    public function execute(string $studentId, array $data): CareerProfileDto
    {
        $validated = Validator::make($data, [
            'title' => 'required|string|max:255',
            'target_date' => 'required|date|after_or_equal:today',
        ])->validate();

        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $profile->createCareerGoal(
            id: CareerGoalId::generate(),
            title: $validated['title'],
            targetDate: new DateTimeImmutable($validated['target_date']),
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toCareerProfileDto($profile);
    }
}
