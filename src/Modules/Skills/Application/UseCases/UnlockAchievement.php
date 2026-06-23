<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Application\DTOs\AchievementDto;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Events\AchievementUnlocked;
use Modules\Skills\Domain\Services\AchievementUnlocker;

final readonly class UnlockAchievement
{
    public function __construct(
        private AchievementRepositoryInterface $achievements,
        private SkillProfileRepositoryInterface $profiles,
        private AchievementUnlocker $unlocker,
        private EventDispatcherInterface $events,
        private SkillsMapper $mapper,
    ) {}

    /**
     * @return array<AchievementDto>
     */
    public function execute(
        string $studentId,
        int $completedCoursesCount = 0,
        int $completedTasksCount = 0,
        int $completedGoalsCount = 0,
    ): array {
        $sid = StudentId::of($studentId);
        $existing = $this->achievements->findByStudentId($sid);
        $profile = $this->profiles->findByStudentId($sid);

        $newAchievements = $this->unlocker->checkAndUnlock(
            studentId: $sid,
            existingAchievements: $existing,
            skillProfile: $profile,
            completedCoursesCount: $completedCoursesCount,
            completedTasksCount: $completedTasksCount,
            completedGoalsCount: $completedGoalsCount,
        );

        $dtos = [];
        foreach ($newAchievements as $achievement) {
            $this->achievements->save($achievement);
            $this->events->dispatch([
                new AchievementUnlocked(
                    achievementId: $achievement->id()->value(),
                    studentId: $achievement->studentId()->value(),
                    title: $achievement->title(),
                    type: $achievement->type()->value,
                    occurredAt: new DateTimeImmutable(),
                ),
            ]);
            $dtos[] = $this->mapper->toAchievementDto($achievement);
        }

        return $dtos;
    }
}
