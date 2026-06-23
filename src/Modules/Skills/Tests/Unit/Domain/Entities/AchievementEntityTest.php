<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use PHPUnit\Framework\TestCase;

final class AchievementEntityTest extends TestCase
{
    public function test_create_returns_achievement(): void
    {
        $id = AchievementId::generate();
        $studentId = StudentId::generate();

        $achievement = Achievement::create(
            $id, $studentId, AchievementType::ACADEMIC,
            'النجم الأكاديمي', 'إكمال 5 مساقات دراسية بنجاح.',
        );

        $this->assertSame($id, $achievement->id());
        $this->assertSame($studentId, $achievement->studentId());
        $this->assertSame(AchievementType::ACADEMIC, $achievement->type());
        $this->assertSame('النجم الأكاديمي', $achievement->title());
        $this->assertSame('إكمال 5 مساقات دراسية بنجاح.', $achievement->description());
        $this->assertNull($achievement->badgeUrl());
        $this->assertInstanceOf(DateTimeImmutable::class, $achievement->unlockedAt());
    }

    public function test_create_with_badge_url(): void
    {
        $id = AchievementId::generate();
        $studentId = StudentId::generate();

        $achievement = Achievement::create(
            $id, $studentId, AchievementType::PRODUCTIVITY,
            'سيد الإنتاجية', 'إنجاز 10 مهام', '/assets/badges/productivity_master.png',
        );

        $this->assertSame('/assets/badges/productivity_master.png', $achievement->badgeUrl());
    }

    public function test_reconstitute_restores_achievement(): void
    {
        $id = AchievementId::generate();
        $studentId = StudentId::generate();
        $unlockedAt = new DateTimeImmutable('2026-06-01 10:00:00');

        $achievement = Achievement::reconstitute(
            $id, $studentId, AchievementType::COMMUNITY,
            'قائد مجتمعي', 'المشاركة في فعاليات مجتمعية',
            '/assets/badges/community_leader.png', $unlockedAt,
        );

        $this->assertSame('قائد مجتمعي', $achievement->title());
        $this->assertSame($unlockedAt, $achievement->unlockedAt());
    }

    public function test_create_sets_unlocked_at_to_now(): void
    {
        $before = new DateTimeImmutable;
        $achievement = Achievement::create(
            AchievementId::generate(), StudentId::generate(),
            AchievementType::CAREER, 'جامع المهارات', 'إضافة 5 مهارات مهنية',
        );
        $after = new DateTimeImmutable;

        $this->assertGreaterThanOrEqual($before->getTimestamp(), $achievement->unlockedAt()->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $achievement->unlockedAt()->getTimestamp());
    }
}
