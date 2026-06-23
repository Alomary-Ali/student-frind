<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Application\DTOs\SkillProfileDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;

final readonly class AddSkill
{
    public function __construct(
        private SkillProfileRepositoryInterface $profiles,
        private EventDispatcherInterface $events,
        private SkillsMapper $mapper,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     *
     * @throws ValidationException
     */
    public function execute(string $studentId, array $data): SkillProfileDto
    {
        $validated = Validator::make($data, [
            'name' => 'required|string|max:100',
            'category' => 'required|in:' . implode(',', array_column(SkillCategory::cases(), 'value')),
            'level' => 'required|in:' . implode(',', array_column(SkillLevel::cases(), 'value')),
            'years_of_experience' => 'nullable|integer|min:0|max:50',
        ])->validate();

        $sid = StudentId::of($studentId);
        $profile = $this->profiles->findByStudentId($sid);

        if ($profile === null) {
            $profile = SkillProfile::create(
                id: SkillProfileId::generate(),
                studentId: $sid,
            );
        }

        $profile->addSkill(
            id: SkillId::generate(),
            name: $validated['name'],
            category: SkillCategory::from($validated['category']),
            level: SkillLevel::from($validated['level']),
            yearsOfExperience: (int) ($validated['years_of_experience'] ?? 0),
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toSkillProfileDto($profile);
    }
}
