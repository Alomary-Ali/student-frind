<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerDashboardDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Services\CareerScoreCalculator;
use Modules\CareerProfile\Domain\Services\LinkedInOptimizer;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;

final readonly class GetCareerDashboard
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private SkillProfileRepositoryInterface $skillProfiles,
        private CareerScoreCalculator $scoreCalculator,
        private LinkedInOptimizer $linkedInOptimizer,
        private CareerProfileMapper $mapper,
    ) {}

    public function execute(string $studentId, ?float $gpa = null): CareerDashboardDto
    {
        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return new CareerDashboardDto(
                profile: null,
                careerScore: 0,
                linkedInScore: 0,
                portfolioItems: [],
                experiences: [],
                careerGoals: [],
                skillCount: 0,
                certificationCount: 0,
            );
        }

        $skillProfile = $this->skillProfiles->findByStudentId(StudentId::of($studentId));

        $careerScore = $this->scoreCalculator->calculate(
            profile: $profile,
            skillProfile: $skillProfile,
            gpa: $gpa ?? 0.0,
        );

        $linkedInResult = $this->linkedInOptimizer->optimize(
            profile: $profile,
            skillProfile: $skillProfile,
        );

        $skillCount = $skillProfile !== null ? count($skillProfile->skills()) : 0;
        $certCount = $skillProfile !== null ? count($skillProfile->certifications()) : 0;

        return new CareerDashboardDto(
            profile: $this->mapper->toCareerProfileDto($profile),
            careerScore: $careerScore,
            linkedInScore: $linkedInResult['score'],
            portfolioItems: array_map([$this->mapper, 'toPortfolioItemDto'], $profile->portfolioItems()),
            experiences: array_map([$this->mapper, 'toExperienceDto'], $profile->experiences()),
            careerGoals: array_map([$this->mapper, 'toCareerGoalDto'], $profile->careerGoals()),
            skillCount: $skillCount,
            certificationCount: $certCount,
        );
    }
}
