<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Gateways;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Career\Domain\Contracts\Gateways\CareerProfileGatewayInterface;
use Modules\CareerProfile\Domain\Contracts\CareerGoalRepositoryInterface;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Contracts\ExperienceRepositoryInterface;
use Modules\CareerProfile\Domain\Contracts\PortfolioItemRepositoryInterface;
use Modules\CareerProfile\Domain\Contracts\ResumeRepositoryInterface;

final class CareerProfileGateway implements CareerProfileGatewayInterface
{
    public function __construct(
        private readonly CareerProfileRepositoryInterface $profileRepo,
        private readonly PortfolioItemRepositoryInterface $portfolioRepo,
        private readonly ExperienceRepositoryInterface $experienceRepo,
        private readonly ResumeRepositoryInterface $resumeRepo,
        private readonly CareerGoalRepositoryInterface $goalRepo,
    ) {}

    public function getProfile(string $studentId): ?array
    {
        $profile = $this->profileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return null;
        }

        return [
            'id' => $profile->id()->value(),
            'student_id' => $profile->studentId()->value(),
            'major' => $profile->major(),
            'summary' => $profile->summary(),
            'interests' => $profile->interests(),
            'languages' => $profile->languages(),
        ];
    }

    public function getPortfolioItems(string $studentId): array
    {
        $profile = $this->profileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return [];
        }

        return array_map(fn ($item) => [
            'id' => $item->id()->value(),
            'title' => $item->title(),
            'description' => $item->description(),
            'project_url' => $item->projectUrl(),
            'github_url' => $item->githubUrl(),
            'technologies' => $item->technologies(),
        ], $profile->portfolioItems());
    }

    public function getExperiences(string $studentId): array
    {
        $profile = $this->profileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return [];
        }

        return array_map(fn ($exp) => [
            'id' => $exp->id()->value(),
            'company' => $exp->company(),
            'position' => $exp->position(),
            'description' => $exp->description(),
            'is_current' => $exp->isCurrent(),
        ], $profile->experiences());
    }

    public function getResumes(string $studentId): array
    {
        $profile = $this->profileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return [];
        }

        return array_map(fn ($resume) => [
            'id' => $resume->id()->value(),
            'template' => $resume->template()->value,
            'generated_at' => $resume->generatedAt()->format('Y-m-d H:i:s'),
        ], $profile->resumes());
    }

    public function getCareerGoals(string $studentId): array
    {
        $profile = $this->profileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return [];
        }

        return array_map(fn ($goal) => [
            'id' => $goal->id()->value(),
            'title' => $goal->title(),
            'target_date' => $goal->targetDate()->format('Y-m-d'),
            'status' => $goal->status()->value,
            'progress' => $goal->progress(),
        ], $profile->careerGoals());
    }

    public function getDashboard(string $studentId, ?float $gpa = null): ?array
    {
        $profile = $this->profileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return null;
        }

        return [
            'profile' => [
                'id' => $profile->id()->value(),
                'major' => $profile->major(),
                'summary' => $profile->summary(),
            ],
            'portfolio_items' => $this->getPortfolioItems($studentId),
            'experiences' => $this->getExperiences($studentId),
            'career_goals' => $this->getCareerGoals($studentId),
            'resumes' => $this->getResumes($studentId),
        ];
    }
}
