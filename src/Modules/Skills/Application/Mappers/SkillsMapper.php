<?php

declare(strict_types=1);

namespace Modules\Skills\Application\Mappers;

use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Application\DTOs\SkillProfileDto;
use Modules\Skills\Application\DTOs\SkillDto;
use Modules\Skills\Application\DTOs\CertificationDto;
use Modules\Skills\Application\DTOs\AchievementDto;
use Modules\Skills\Application\DTOs\LearningPathDto;

final class SkillsMapper
{
    public function toSkillProfileDto(SkillProfile $profile): SkillProfileDto
    {
        return new SkillProfileDto(
            id: $profile->id()->value(),
            studentId: $profile->studentId()->value(),
            skills: array_map([$this, 'toSkillDto'], $profile->skills()),
            certifications: array_map([$this, 'toCertificationDto'], $profile->certifications()),
        );
    }

    public function toSkillDto(Skill $skill): SkillDto
    {
        return new SkillDto(
            id: $skill->id()->value(),
            skillProfileId: $skill->skillProfileId()->value(),
            name: $skill->name(),
            category: $skill->category()->value,
            categoryLabel: $skill->category()->label(),
            level: $skill->level()->value,
            levelLabel: $skill->level()->label(),
            levelWeight: $skill->level()->weight(),
            yearsOfExperience: $skill->yearsOfExperience(),
            lastUsed: $skill->lastUsed()->format('Y-m-d'),
        );
    }

    public function toCertificationDto(Certification $cert): CertificationDto
    {
        return new CertificationDto(
            id: $cert->id()->value(),
            skillProfileId: $cert->skillProfileId()->value(),
            name: $cert->name(),
            issuer: $cert->issuer(),
            issueDate: $cert->issueDate()->format('Y-m-d'),
            expiryDate: $cert->expiryDate()?->format('Y-m-d'),
            credentialUrl: $cert->credentialUrl(),
            verificationCode: $cert->verificationCode(),
            isExpired: $cert->isExpired(),
        );
    }

    public function toAchievementDto(Achievement $achievement): AchievementDto
    {
        return new AchievementDto(
            id: $achievement->id()->value(),
            studentId: $achievement->studentId()->value(),
            type: $achievement->type()->value,
            typeLabel: $achievement->type()->label(),
            title: $achievement->title(),
            description: $achievement->description(),
            badgeUrl: $achievement->badgeUrl(),
            unlockedAt: $achievement->unlockedAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toLearningPathDto(LearningPath $path): LearningPathDto
    {
        return new LearningPathDto(
            id: $path->id()->value(),
            studentId: $path->studentId()->value(),
            title: $path->title(),
            targetRole: $path->targetRole(),
            steps: $path->steps(),
            progress: $path->progress(),
            estimatedCompletionDate: $path->estimatedCompletionDate()?->format('Y-m-d'),
        );
    }
}
