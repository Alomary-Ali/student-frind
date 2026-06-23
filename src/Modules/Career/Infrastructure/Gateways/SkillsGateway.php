<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Gateways;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;

final class SkillsGateway implements SkillsGatewayInterface
{
    public function __construct(
        private readonly SkillProfileRepositoryInterface $skillProfileRepo,
        private readonly AchievementRepositoryInterface $achievementRepo,
        private readonly LearningPathRepositoryInterface $learningPathRepo,
    ) {}

    public function getSkillProfile(string $studentId): ?array
    {
        $profile = $this->skillProfileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return null;
        }

        return [
            'id' => $profile->id()->value(),
            'student_id' => $profile->studentId()->value(),
            'skills' => $this->getSkills($studentId),
            'certifications' => $this->getCertifications($studentId),
        ];
    }

    public function getSkills(string $studentId): array
    {
        $profile = $this->skillProfileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return [];
        }

        return array_map(fn ($skill) => [
            'id' => $skill->id()->value(),
            'name' => $skill->name(),
            'category' => $skill->category()->value,
            'level' => $skill->level()->value,
            'years_of_experience' => $skill->yearsOfExperience(),
        ], $profile->skills());
    }

    public function getCertifications(string $studentId): array
    {
        $profile = $this->skillProfileRepo->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return [];
        }

        return array_map(fn ($cert) => [
            'id' => $cert->id()->value(),
            'name' => $cert->name(),
            'issuer' => $cert->issuer(),
            'issue_date' => $cert->issueDate()->format('Y-m-d'),
            'expiry_date' => $cert->expiryDate()?->format('Y-m-d'),
        ], $profile->certifications());
    }

    public function getAchievements(string $studentId): array
    {
        $achievements = $this->achievementRepo->findByStudentId(StudentId::of($studentId));

        return array_map(fn ($a) => [
            'id' => $a->id()->value(),
            'title' => $a->title(),
            'type' => $a->type()->value,
            'badge_url' => $a->badgeUrl(),
            'unlocked_at' => $a->unlockedAt()->format('Y-m-d H:i:s'),
        ], $achievements);
    }

    public function getLearningPaths(string $studentId): array
    {
        $paths = $this->learningPathRepo->findByStudentId(StudentId::of($studentId));

        return array_map(fn ($lp) => [
            'id' => $lp->id()->value(),
            'title' => $lp->title(),
            'target_role' => $lp->targetRole(),
            'progress' => $lp->progress(),
            'steps' => $lp->steps(),
        ], $paths);
    }
}
