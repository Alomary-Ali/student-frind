<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Application\DTOs\LearningPathDto;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Events\LearningPathCreated;
use Modules\Skills\Domain\Services\LearningPathGenerator;
use Modules\Skills\Domain\Services\SkillGapAnalyzer;
use Modules\Skills\Domain\ValueObjects\LearningPathId;

final readonly class CreateLearningPath
{
    public function __construct(
        private LearningPathRepositoryInterface $paths,
        private SkillProfileRepositoryInterface $profiles,
        private SkillGapAnalyzer $gapAnalyzer,
        private LearningPathGenerator $pathGenerator,
        private EventDispatcherInterface $events,
        private SkillsMapper $mapper,
    ) {}

    public function execute(string $studentId, string $targetRole): LearningPathDto
    {
        $sid = StudentId::of($studentId);
        $profile = $this->profiles->findByStudentId($sid);

        if ($profile === null) {
            throw new \RuntimeException('Skill profile not found. Create a skill profile first.');
        }

        $gapReport = $this->gapAnalyzer->analyze($profile, $targetRole);
        $missingSkills = $gapReport['missing_skills'];

        $learningPath = $this->pathGenerator->generate(
            id: LearningPathId::generate(),
            studentId: $sid,
            roleKey: $targetRole,
            missingSkills: $missingSkills,
        );

        $this->paths->save($learningPath);
        $this->events->dispatch([
            new LearningPathCreated(
                learningPathId: $learningPath->id()->value(),
                studentId: $learningPath->studentId()->value(),
                title: $learningPath->title(),
                targetRole: $learningPath->targetRole(),
                occurredAt: new DateTimeImmutable(),
            ),
        ]);

        return $this->mapper->toLearningPathDto($learningPath);
    }
}
