<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Skills\Domain\Exceptions\InvalidSkillProfileIdException;
use Modules\Skills\Domain\Exceptions\InvalidSkillIdException;
use Modules\Skills\Domain\Exceptions\InvalidCertificationIdException;
use Modules\Skills\Domain\Exceptions\InvalidAchievementIdException;
use Modules\Skills\Domain\Exceptions\InvalidLearningPathIdException;

final class SkillsValueObjectsTest extends TestCase
{
    private const VALID_UUID = '550e8400-e29b-41d4-a716-446655440000';
    private const INVALID_UUID = 'not-a-uuid';

    public function test_skill_profile_id_generate_creates_valid_uuid(): void
    {
        $id = SkillProfileId::generate();
        $this->assertInstanceOf(SkillProfileId::class, $id);
        $this->assertNotEmpty($id->value());
    }

    public function test_skill_profile_id_from_string_creates_from_valid_uuid(): void
    {
        $id = SkillProfileId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_skill_profile_id_from_string_throws_for_invalid_uuid(): void
    {
        $this->expectException(InvalidSkillProfileIdException::class);
        SkillProfileId::fromString(self::INVALID_UUID);
    }

    public function test_skill_profile_id_equals_returns_true_for_same_value(): void
    {
        $a = SkillProfileId::fromString(self::VALID_UUID);
        $b = SkillProfileId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_skill_profile_id_equals_returns_false_for_different_value(): void
    {
        $a = SkillProfileId::fromString(self::VALID_UUID);
        $b = SkillProfileId::generate();
        $this->assertFalse($a->equals($b));
    }

    public function test_skill_profile_id_of_creates_instance(): void
    {
        $id = SkillProfileId::of(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_skill_profile_id_to_string_returns_value(): void
    {
        $id = SkillProfileId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, (string) $id);
    }

    public function test_skill_id_generate_creates_valid_uuid(): void
    {
        $id = SkillId::generate();
        $this->assertInstanceOf(SkillId::class, $id);
    }

    public function test_skill_id_from_string_valid(): void
    {
        $id = SkillId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_skill_id_from_string_invalid_throws(): void
    {
        $this->expectException(InvalidSkillIdException::class);
        SkillId::fromString(self::INVALID_UUID);
    }

    public function test_skill_id_equals(): void
    {
        $a = SkillId::fromString(self::VALID_UUID);
        $b = SkillId::fromString(self::VALID_UUID);
        $c = SkillId::generate();
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_certification_id_generate_creates_valid_uuid(): void
    {
        $id = CertificationId::generate();
        $this->assertInstanceOf(CertificationId::class, $id);
    }

    public function test_certification_id_from_string_valid(): void
    {
        $id = CertificationId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_certification_id_from_string_invalid_throws(): void
    {
        $this->expectException(InvalidCertificationIdException::class);
        CertificationId::fromString(self::INVALID_UUID);
    }

    public function test_certification_id_equals(): void
    {
        $a = CertificationId::fromString(self::VALID_UUID);
        $b = CertificationId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_achievement_id_generate_creates_valid_uuid(): void
    {
        $id = AchievementId::generate();
        $this->assertInstanceOf(AchievementId::class, $id);
    }

    public function test_achievement_id_from_string_valid(): void
    {
        $id = AchievementId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_achievement_id_from_string_invalid_throws(): void
    {
        $this->expectException(InvalidAchievementIdException::class);
        AchievementId::fromString(self::INVALID_UUID);
    }

    public function test_achievement_id_equals(): void
    {
        $a = AchievementId::fromString(self::VALID_UUID);
        $b = AchievementId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_learning_path_id_generate_creates_valid_uuid(): void
    {
        $id = LearningPathId::generate();
        $this->assertInstanceOf(LearningPathId::class, $id);
    }

    public function test_learning_path_id_from_string_valid(): void
    {
        $id = LearningPathId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_learning_path_id_from_string_invalid_throws(): void
    {
        $this->expectException(InvalidLearningPathIdException::class);
        LearningPathId::fromString(self::INVALID_UUID);
    }

    public function test_learning_path_id_equals(): void
    {
        $a = LearningPathId::fromString(self::VALID_UUID);
        $b = LearningPathId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }
}
