<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use Modules\Skills\Application\DTOs\SkillProfileDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class GetOrCreateSkillProfile
{
    public function __construct(
        private SkillProfileRepositoryInterface $profiles,
        private EventDispatcherInterface $events,
        private SkillsMapper $mapper,
    ) {
    }

    public function execute(string $studentId): SkillProfileDto
    {
        $sid = StudentId::of($studentId);
        $profile = $this->profiles->findByStudentId($sid);

        if ($profile === null) {
            $profile = SkillProfile::create(
                id: SkillProfileId::generate(),
                studentId: $sid,
            );
            $this->profiles->save($profile);
        }

        return $this->mapper->toSkillProfileDto($profile);
    }
}
