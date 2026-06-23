<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\LinkedInOptimizationReportDto;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Services\LinkedInOptimizer;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;

final readonly class OptimizeLinkedIn
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private SkillProfileRepositoryInterface $skillProfiles,
        private LinkedInOptimizer $optimizer,
    ) {}

    public function execute(string $studentId): LinkedInOptimizationReportDto
    {
        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $skillProfile = $this->skillProfiles->findByStudentId(StudentId::of($studentId));

        $result = $this->optimizer->optimize($profile, $skillProfile);

        return new LinkedInOptimizationReportDto(
            score: $result['score'],
            recommendations: $result['recommendations'],
        );
    }
}
