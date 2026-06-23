<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Application\DTOs;

use Modules\Skills\Application\DTOs\AchievementDto;
use Modules\Skills\Application\DTOs\CertificationDto;
use Modules\Skills\Application\DTOs\LearningPathDto;
use Modules\Skills\Application\DTOs\SkillDto;
use Modules\Skills\Application\DTOs\SkillProfileDto;
use PHPUnit\Framework\TestCase;

final class SkillsDtoTest extends TestCase
{
    public function test_skill_profile_dto_constructs(): void
    {
        $skills = [
            new SkillDto('s1', 'p1', 'PHP', 'programming', 'البرمجة', 'advanced', 'متقدم', 3, 5, '2026-01-01'),
        ];
        $certs = [
            new CertificationDto('c1', 'p1', 'AWS', 'Amazon', '2026-01-15', null, null, null, false),
        ];

        $dto = new SkillProfileDto('p1', 'stu1', $skills, $certs);

        $this->assertSame('p1', $dto->id);
        $this->assertSame('stu1', $dto->studentId);
        $this->assertCount(1, $dto->skills);
        $this->assertCount(1, $dto->certifications);
    }

    public function test_skill_dto_constructs(): void
    {
        $dto = new SkillDto(
            id: 's1',
            skillProfileId: 'p1',
            name: 'Laravel',
            category: 'programming',
            categoryLabel: 'البرمجة والتطوير',
            level: 'advanced',
            levelLabel: 'متقدم',
            levelWeight: 3,
            yearsOfExperience: 4,
            lastUsed: '2026-06-01',
        );

        $this->assertSame('s1', $dto->id);
        $this->assertSame('Laravel', $dto->name);
        $this->assertSame('programming', $dto->category);
        $this->assertSame('البرمجة والتطوير', $dto->categoryLabel);
        $this->assertSame('advanced', $dto->level);
        $this->assertSame('متقدم', $dto->levelLabel);
        $this->assertSame(3, $dto->levelWeight);
        $this->assertSame(4, $dto->yearsOfExperience);
        $this->assertSame('2026-06-01', $dto->lastUsed);
    }

    public function test_certification_dto_constructs(): void
    {
        $dto = new CertificationDto(
            id: 'c1',
            skillProfileId: 'p1',
            name: 'OCA Java',
            issuer: 'Oracle',
            issueDate: '2026-01-15',
            expiryDate: '2030-01-15',
            credentialUrl: 'https://example.com/cert',
            verificationCode: 'VER123',
            isExpired: false,
        );

        $this->assertSame('c1', $dto->id);
        $this->assertSame('OCA Java', $dto->name);
        $this->assertSame('Oracle', $dto->issuer);
        $this->assertSame('https://example.com/cert', $dto->credentialUrl);
        $this->assertFalse($dto->isExpired);
    }

    public function test_certification_dto_with_null_optionals(): void
    {
        $dto = new CertificationDto('c1', 'p1', 'Cert', 'Issuer', '2026-01-01', null, null, null, false);

        $this->assertNull($dto->expiryDate);
        $this->assertNull($dto->credentialUrl);
        $this->assertNull($dto->verificationCode);
    }

    public function test_achievement_dto_constructs(): void
    {
        $dto = new AchievementDto(
            id: 'a1',
            studentId: 'stu1',
            type: 'academic',
            typeLabel: 'أكاديمي',
            title: 'النجم الأكاديمي',
            description: 'إكمال 5 مساقات',
            badgeUrl: '/assets/badges/academic_star.png',
            unlockedAt: '2026-06-01 10:00:00',
        );

        $this->assertSame('a1', $dto->id);
        $this->assertSame('academic', $dto->type);
        $this->assertSame('أكاديمي', $dto->typeLabel);
        $this->assertSame('/assets/badges/academic_star.png', $dto->badgeUrl);
    }

    public function test_achievement_dto_with_null_badge(): void
    {
        $dto = new AchievementDto('a1', 'stu1', 'career', 'تطوير مهني', 'Title', 'Desc', null, '2026-06-01 10:00:00');

        $this->assertNull($dto->badgeUrl);
    }

    public function test_learning_path_dto_constructs(): void
    {
        $steps = [
            ['id' => 'step-1', 'title' => 'Learn PHP', 'completed' => false],
        ];
        $dto = new LearningPathDto(
            id: 'lp1',
            studentId: 'stu1',
            title: 'مسار تعلم Laravel',
            targetRole: 'backend_developer',
            steps: $steps,
            progress: 0,
            estimatedCompletionDate: '2026-12-31',
        );

        $this->assertSame('lp1', $dto->id);
        $this->assertSame('مسار تعلم Laravel', $dto->title);
        $this->assertCount(1, $dto->steps);
        $this->assertSame(0, $dto->progress);
        $this->assertSame('2026-12-31', $dto->estimatedCompletionDate);
    }

    public function test_learning_path_dto_with_null_completion_date(): void
    {
        $dto = new LearningPathDto('lp1', 'stu1', 'Path', 'role', [], 0, null);

        $this->assertNull($dto->estimatedCompletionDate);
    }
}
