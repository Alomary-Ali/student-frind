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
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\ValueObjects\SkillId;

final readonly class UpdateSkillLevel
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
    public function execute(string $studentId, string $skillId, array $data): SkillProfileDto
    {
        $validated = Validator::make($data, [
            'level' => 'required|in:' . implode(',', array_column(SkillLevel::cases(), 'value')),
        ])->validate();

        $sid = StudentId::of($studentId);
        $profile = $this->profiles->findByStudentId($sid);

        if ($profile === null) {
            throw new \RuntimeException('Skill profile not found. Create a skill profile first.');
        }

        $profile->updateSkillLevel(
            skillId: SkillId::of($skillId),
            newLevel: SkillLevel::from($validated['level']),
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toSkillProfileDto($profile);
    }
}
