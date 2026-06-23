<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Domain\Contracts\Gateways\CareerProfileGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;

final readonly class CalculateEmploymentReadiness
{
    private const GPA_WEIGHT = 0.25;
    private const SKILLS_WEIGHT = 0.30;
    private const EXPERIENCE_WEIGHT = 0.20;
    private const CERTIFICATION_WEIGHT = 0.15;
    private const GOALS_WEIGHT = 0.10;

    public function __construct(
        private CareerProfileGatewayInterface $careerProfileGateway,
        private SkillsGatewayInterface $skillsGateway,
    ) {}

    public function execute(string $studentId, ?float $gpa = null): array
    {
        $profile = $this->careerProfileGateway->getProfile($studentId);
        $skillsData = $this->skillsGateway->getSkills($studentId);
        $certifications = $this->skillsGateway->getCertifications($studentId);
        $experiences = $this->careerProfileGateway->getExperiences($studentId);
        $goals = $this->careerProfileGateway->getCareerGoals($studentId);

        $gpaScore = $this->calculateGpaScore($gpa);
        $skillsScore = $this->calculateSkillsScore($skillsData);
        $experienceScore = $this->calculateExperienceScore($experiences);
        $certificationScore = $this->calculateCertificationScore($certifications);
        $goalsScore = $this->calculateGoalsScore($goals);

        $total = ($gpaScore * self::GPA_WEIGHT)
            + ($skillsScore * self::SKILLS_WEIGHT)
            + ($experienceScore * self::EXPERIENCE_WEIGHT)
            + ($certificationScore * self::CERTIFICATION_WEIGHT)
            + ($goalsScore * self::GOALS_WEIGHT);

        return [
            'score' => round(min(100, max(0, $total)), 1),
            'breakdown' => [
                'gpa' => ['score' => $gpaScore, 'weight' => self::GPA_WEIGHT, 'contribution' => round($gpaScore * self::GPA_WEIGHT, 1)],
                'skills' => ['score' => $skillsScore, 'weight' => self::SKILLS_WEIGHT, 'contribution' => round($skillsScore * self::SKILLS_WEIGHT, 1)],
                'experience' => ['score' => $experienceScore, 'weight' => self::EXPERIENCE_WEIGHT, 'contribution' => round($experienceScore * self::EXPERIENCE_WEIGHT, 1)],
                'certifications' => ['score' => $certificationScore, 'weight' => self::CERTIFICATION_WEIGHT, 'contribution' => round($certificationScore * self::CERTIFICATION_WEIGHT, 1)],
                'goals' => ['score' => $goalsScore, 'weight' => self::GOALS_WEIGHT, 'contribution' => round($goalsScore * self::GOALS_WEIGHT, 1)],
            ],
        ];
    }

    private function calculateGpaScore(?float $gpa): float
    {
        if ($gpa === null || $gpa <= 0) {
            return 0;
        }

        return min(100, ($gpa / 4.0) * 100);
    }

    private function calculateSkillsScore(array $skills): float
    {
        if (empty($skills)) {
            return 0;
        }

        $totalLevels = 0;
        foreach ($skills as $skill) {
            $levelMap = ['beginner' => 25, 'intermediate' => 50, 'advanced' => 75, 'expert' => 100];
            $totalLevels += $levelMap[$skill['level'] ?? 'beginner'] ?? 25;
        }

        return round($totalLevels / count($skills), 1);
    }

    private function calculateExperienceScore(array $experiences): float
    {
        if (empty($experiences)) {
            return 0;
        }

        $count = count($experiences);

        return min(100, $count * 25);
    }

    private function calculateCertificationScore(array $certifications): float
    {
        if (empty($certifications)) {
            return 0;
        }

        return min(100, count($certifications) * 33.3);
    }

    private function calculateGoalsScore(array $goals): float
    {
        if (empty($goals)) {
            return 0;
        }

        $totalProgress = 0;
        foreach ($goals as $goal) {
            $totalProgress += $goal['progress'] ?? 0;
        }

        return round($totalProgress / count($goals), 1);
    }
}
