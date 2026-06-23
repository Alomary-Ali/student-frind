<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Application\DTOs\AchievementDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;

final readonly class GetStudentAchievements
{
    public function __construct(
        private AchievementRepositoryInterface $achievements,
        private SkillsMapper $mapper,
    ) {}

    /**
     * @return array<AchievementDto>
     */
    public function execute(string $studentId): array
    {
        $sid = StudentId::of($studentId);
        $achievements = $this->achievements->findByStudentId($sid);

        return array_map(
            fn ($a) => $this->mapper->toAchievementDto($a),
            $achievements,
        );
    }
}
