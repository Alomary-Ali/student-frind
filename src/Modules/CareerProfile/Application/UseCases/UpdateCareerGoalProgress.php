<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerGoalDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;

final readonly class UpdateCareerGoalProgress
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private CareerProfileMapper $mapper,
    ) {}

    public function execute(string $studentId, string $goalId, int $progress): CareerGoalDto
    {
        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $profile->updateCareerGoalProgress(CareerGoalId::of($goalId), $progress);

        $this->profiles->save($profile);

        $goal = null;
        foreach ($profile->careerGoals() as $g) {
            if ($g->id()->value() === $goalId) {
                $goal = $g;
                break;
            }
        }

        if ($goal === null) {
            throw new \RuntimeException("Career goal not found: {$goalId}");
        }

        return $this->mapper->toCareerGoalDto($goal);
    }
}
