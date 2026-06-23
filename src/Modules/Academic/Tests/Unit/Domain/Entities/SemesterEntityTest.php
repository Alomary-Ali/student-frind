<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\Semester;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use PHPUnit\Framework\TestCase;

final class SemesterEntityTest extends TestCase
{
    public function test_semester_can_be_created(): void
    {
        $semester = Semester::create(
            SemesterId::generate(),
            'First Semester 2026',
            'SEM-2026-1',
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-06-30'),
        );

        $this->assertSame('First Semester 2026', $semester->name());
        $this->assertSame('SEM-2026-1', $semester->code());
        $this->assertTrue($semester->isActive());
    }

    public function test_semester_can_be_reconstituted(): void
    {
        $semester = Semester::reconstitute(
            SemesterId::generate(),
            'First Semester 2026',
            'SEM-2026-1',
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-06-30'),
            false,
            null,
            new DateTimeImmutable('2026-01-01'),
        );

        $this->assertFalse($semester->isActive());
        $this->assertSame('First Semester 2026', $semester->name());
    }

    public function test_is_currently_active_returns_true_when_within_dates(): void
    {
        $semester = Semester::create(
            SemesterId::generate(),
            'Current Semester',
            'CUR-2026',
            new DateTimeImmutable('-30 days'),
            new DateTimeImmutable('+30 days'),
        );

        $this->assertTrue($semester->isCurrentlyActive());
    }

    public function test_is_currently_active_returns_false_when_before_start(): void
    {
        $semester = Semester::reconstitute(
            SemesterId::generate(),
            'Future Semester',
            'FUT-2026',
            new DateTimeImmutable('+30 days'),
            new DateTimeImmutable('+90 days'),
            true,
            null,
            new DateTimeImmutable(),
        );

        $this->assertFalse($semester->isCurrentlyActive());
    }

    public function test_is_currently_active_returns_false_when_inactive(): void
    {
        $semester = Semester::reconstitute(
            SemesterId::generate(),
            'Inactive',
            'INA-2026',
            new DateTimeImmutable('-30 days'),
            new DateTimeImmutable('+30 days'),
            false,
            null,
            new DateTimeImmutable(),
        );

        $this->assertFalse($semester->isCurrentlyActive());
    }

    public function test_semester_getters_return_correct_values(): void
    {
        $id = SemesterId::generate();
        $start = new DateTimeImmutable('2026-01-01');
        $end = new DateTimeImmutable('2026-06-30');
        $createdAt = new DateTimeImmutable();
        $institutionId = 'inst-1';

        $semester = Semester::reconstitute(
            $id,
            'Test Semester',
            'TS-2026',
            $start,
            $end,
            true,
            $institutionId,
            $createdAt,
        );

        $this->assertTrue($id->equals($semester->id()));
        $this->assertSame('Test Semester', $semester->name());
        $this->assertSame('TS-2026', $semester->code());
        $this->assertSame($start, $semester->startDate());
        $this->assertSame($end, $semester->endDate());
        $this->assertTrue($semester->isActive());
        $this->assertSame($institutionId, $semester->institutionId());
        $this->assertSame($createdAt, $semester->createdAt());
    }
}
