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
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class AddPortfolioItem
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
            'description' => 'required|string|max:2000',
            'project_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'technologies' => 'nullable|array',
        ])->validate();

        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $profile->addPortfolioItem(
            id: PortfolioItemId::generate(),
            title: $validated['title'],
            description: $validated['description'],
            projectUrl: $validated['project_url'] ?? null,
            githubUrl: $validated['github_url'] ?? null,
            startDate: new DateTimeImmutable($validated['start_date']),
            endDate: isset($validated['end_date']) ? new DateTimeImmutable($validated['end_date']) : null,
            technologies: $validated['technologies'] ?? [],
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toCareerProfileDto($profile);
    }
}
