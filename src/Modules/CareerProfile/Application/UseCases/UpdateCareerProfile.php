<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;

final readonly class UpdateCareerProfile
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
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
            'major' => 'required|string|max:255',
            'summary' => 'nullable|string|max:2000',
            'interests' => 'nullable|array',
            'languages' => 'nullable|array',
        ])->validate();

        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $profile->updateProfile(
            major: $validated['major'],
            summary: $validated['summary'] ?? '',
            interests: $validated['interests'] ?? [],
            languages: $validated['languages'] ?? [],
        );

        $this->profiles->save($profile);

        return $this->mapper->toCareerProfileDto($profile);
    }
}
