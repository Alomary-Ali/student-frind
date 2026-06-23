<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use PHPUnit\Framework\TestCase;

final class ExperienceEntityTest extends TestCase
{
    private ExperienceId $expId;
    private CareerProfileId $profileId;

    protected function setUp(): void
    {
        $this->expId = ExperienceId::generate();
        $this->profileId = CareerProfileId::generate();
    }

    public function test_can_create_experience(): void
    {
        $exp = Experience::create(
            $this->expId,
            $this->profileId,
            'شركة جوجل',
            'مطور برمجيات',
            'تطوير تطبيقات الويب',
            new DateTimeImmutable('2025-01-01'),
            new DateTimeImmutable('2025-12-31'),
            false,
        );

        $this->assertSame($this->expId, $exp->id());
        $this->assertSame($this->profileId, $exp->careerProfileId());
        $this->assertSame('شركة جوجل', $exp->company());
        $this->assertSame('مطور برمجيات', $exp->position());
        $this->assertSame('تطوير تطبيقات الويب', $exp->description());
        $this->assertFalse($exp->isCurrent());
    }

    public function test_can_create_current_experience(): void
    {
        $exp = Experience::create(
            $this->expId,
            $this->profileId,
            'شركة حالية',
            'متدرب',
            'وصف',
            new DateTimeImmutable('2026-01-01'),
            null,
            true,
        );

        $this->assertTrue($exp->isCurrent());
        $this->assertNull($exp->endDate());
    }

    public function test_can_update_experience(): void
    {
        $exp = Experience::create(
            $this->expId,
            $this->profileId,
            'شركة قديمة',
            'مطور مبتدئ',
            'وصف قديم',
            new DateTimeImmutable('2024-01-01'),
            new DateTimeImmutable('2024-12-31'),
            false,
        );

        $exp->update(
            'شركة جديدة',
            'مطور أول',
            'وصف جديد',
            new DateTimeImmutable('2025-06-01'),
            null,
            true,
        );

        $this->assertSame('شركة جديدة', $exp->company());
        $this->assertSame('مطور أول', $exp->position());
        $this->assertSame('وصف جديد', $exp->description());
        $this->assertTrue($exp->isCurrent());
        $this->assertNull($exp->endDate());
    }

    public function test_can_reconstitute_experience(): void
    {
        $startDate = new DateTimeImmutable('2025-01-01');
        $exp = Experience::reconstitute(
            $this->expId,
            $this->profileId,
            'شركة',
            'وظيفة',
            'وصف',
            $startDate,
            null,
            true,
        );

        $this->assertSame('شركة', $exp->company());
        $this->assertSame('وظيفة', $exp->position());
        $this->assertSame($startDate, $exp->startDate());
        $this->assertTrue($exp->isCurrent());
    }
}
