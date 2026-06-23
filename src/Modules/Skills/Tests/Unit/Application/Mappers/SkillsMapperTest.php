<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Application\Mappers;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Application\DTOs\AchievementDto;
use Modules\Skills\Application\DTOs\CertificationDto;
use Modules\Skills\Application\DTOs\LearningPathDto;
use Modules\Skills\Application\DTOs\SkillDto;
use Modules\Skills\Application\DTOs\SkillProfileDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use PHPUnit\Framework\TestCase;

final class SkillsMapperTest extends TestCase
{
    private SkillsMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SkillsMapper;
    }

    public function test_to_skill_profile_dto(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $skillId = SkillId::generate();
        $profile->addSkill($skillId, 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED, 5);

        $profile->addCertification(
            CertificationId::generate(), 'AWS Certified', 'Amazon',
            new DateTimeImmutable('2026-01-15'),
        );

        $dto = $this->mapper->toSkillProfileDto($profile);

        $this->assertInstanceOf(SkillProfileDto::class, $dto);
        $this->assertSame($profileId->value(), $dto->id);
        $this->assertSame($studentId->value(), $dto->studentId);
        $this->assertCount(1, $dto->skills);
        $this->assertCount(1, $dto->certifications);
    }

    public function test_to_skill_dto(): void
    {
        $skill = Skill::create(
            SkillId::fromString('550e8400-e29b-41d4-a716-446655440000'),
            SkillProfileId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            'Laravel',
            SkillCategory::PROGRAMMING,
            SkillLevel::ADVANCED,
            3,
        );

        $dto = $this->mapper->toSkillDto($skill);

        $this->assertInstanceOf(SkillDto::class, $dto);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $dto->id);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440001', $dto->skillProfileId);
        $this->assertSame('Laravel', $dto->name);
        $this->assertSame('programming', $dto->category);
        $this->assertSame('البرمجة والتطوير', $dto->categoryLabel);
        $this->assertSame('advanced', $dto->level);
        $this->assertSame('متقدم', $dto->levelLabel);
        $this->assertSame(3, $dto->levelWeight);
        $this->assertSame(3, $dto->yearsOfExperience);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $dto->lastUsed);
    }

    public function test_to_certification_dto(): void
    {
        $cert = Certification::create(
            CertificationId::fromString('550e8400-e29b-41d4-a716-446655440000'),
            SkillProfileId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            'AWS Solutions Architect',
            'Amazon Web Services',
            new DateTimeImmutable('2026-01-15'),
            new DateTimeImmutable('2028-01-15'),
            'https://aws.amazon.com/cert',
            'AWS-12345',
        );

        $dto = $this->mapper->toCertificationDto($cert);

        $this->assertInstanceOf(CertificationDto::class, $dto);
        $this->assertSame('AWS Solutions Architect', $dto->name);
        $this->assertSame('Amazon Web Services', $dto->issuer);
        $this->assertSame('2026-01-15', $dto->issueDate);
        $this->assertSame('2028-01-15', $dto->expiryDate);
        $this->assertSame('https://aws.amazon.com/cert', $dto->credentialUrl);
        $this->assertSame('AWS-12345', $dto->verificationCode);
        $this->assertFalse($dto->isExpired);
    }

    public function test_to_certification_dto_with_no_expiry(): void
    {
        $cert = Certification::create(
            CertificationId::generate(),
            SkillProfileId::generate(),
            'Some Cert',
            'Issuer',
            new DateTimeImmutable('2026-01-01'),
        );

        $dto = $this->mapper->toCertificationDto($cert);

        $this->assertNull($dto->expiryDate);
        $this->assertFalse($dto->isExpired);
    }

    public function test_to_achievement_dto(): void
    {
        $achievement = Achievement::create(
            AchievementId::fromString('550e8400-e29b-41d4-a716-446655440000'),
            StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            AchievementType::ACADEMIC,
            'النجم الأكاديمي',
            'إكمال 5 مساقات دراسية بنجاح.',
            '/assets/badges/academic_star.png',
        );

        $dto = $this->mapper->toAchievementDto($achievement);

        $this->assertInstanceOf(AchievementDto::class, $dto);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $dto->id);
        $this->assertSame('academic', $dto->type);
        $this->assertSame('أكاديمي', $dto->typeLabel);
        $this->assertSame('النجم الأكاديمي', $dto->title);
        $this->assertSame('/assets/badges/academic_star.png', $dto->badgeUrl);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dto->unlockedAt);
    }

    public function test_to_learning_path_dto(): void
    {
        $steps = [
            ['id' => 'step-1', 'title' => 'Learn PHP', 'completed' => false],
        ];
        $path = LearningPath::create(
            LearningPathId::fromString('550e8400-e29b-41d4-a716-446655440000'),
            StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            'مسار تعلم Laravel',
            'backend_developer',
            $steps,
            new DateTimeImmutable('2026-12-31'),
        );

        $dto = $this->mapper->toLearningPathDto($path);

        $this->assertInstanceOf(LearningPathDto::class, $dto);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $dto->id);
        $this->assertSame('مسار تعلم Laravel', $dto->title);
        $this->assertSame('backend_developer', $dto->targetRole);
        $this->assertCount(1, $dto->steps);
        $this->assertSame(0, $dto->progress);
        $this->assertSame('2026-12-31', $dto->estimatedCompletionDate);
    }
}
