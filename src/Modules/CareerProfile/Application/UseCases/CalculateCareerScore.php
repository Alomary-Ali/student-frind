<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerScoreDto;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Services\CareerScoreCalculator;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;

final readonly class CalculateCareerScore
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private SkillProfileRepositoryInterface $skillProfiles,
        private CareerScoreCalculator $calculator,
    ) {}

    public function execute(string $studentId, float $gpa): CareerScoreDto
    {
        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $skillProfile = $this->skillProfiles->findByStudentId(StudentId::of($studentId));

        $score = $this->calculator->calculate($profile, $skillProfile, $gpa);

        return new CareerScoreDto(
            score: $score,
            breakdown: [
                'skills' => $skillProfile !== null ? count($skillProfile->skills()) : 0,
                'gpa' => $gpa,
                'projects' => count($profile->portfolioItems()),
                'certifications' => $skillProfile !== null ? count($skillProfile->certifications()) : 0,
                'experiences' => count($profile->experiences()),
                'goals_completed' => count(array_filter(
                    $profile->careerGoals(),
                    fn ($g) => $g->status()->value === 'completed',
                )),
            ],
        );
    }
}
