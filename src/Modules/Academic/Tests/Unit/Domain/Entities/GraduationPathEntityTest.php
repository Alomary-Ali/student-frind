<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\GraduationPath;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\GraduationPathId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class GraduationPathEntityTest extends TestCase
{
    public function test_graduation_path_can_be_initialized(): void
    {
        $path = GraduationPath::initialize(
            GraduationPathId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
            Credits::of(20),
        );

        $this->assertSame(0, $path->creditsEarned()->value());
        $this->assertSame(20, $path->creditsRequired()->value());
        $this->assertSame(0.0, $path->completionPercentage());
        $this->assertTrue($path->isOnTrack());
        $this->assertNull($path->estimatedGraduationDate());
    }

    public function test_graduation_path_can_be_reconstituted(): void
    {
        $path = GraduationPath::reconstitute(
            GraduationPathId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
            Credits::of(5),
            Credits::of(20),
            25.0,
            true,
            new DateTimeImmutable('2028-06-30'),
            new DateTimeImmutable('2026-01-01'),
        );

        $this->assertSame(5, $path->creditsEarned()->value());
        $this->assertSame(25.0, $path->completionPercentage());
        $this->assertTrue($path->isOnTrack());
    }

    public function test_update_progress_recalculates_percentage(): void
    {
        $path = GraduationPath::initialize(
            GraduationPathId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
            Credits::of(20),
        );

        $path->updateProgress(Credits::of(10), 3.0);

        $this->assertSame(10, $path->creditsEarned()->value());
        $this->assertSame(50.0, $path->completionPercentage());
        $this->assertTrue($path->isOnTrack());
    }

    public function test_update_progress_marks_off_track_when_below_50_percent(): void
    {
        $path = GraduationPath::initialize(
            GraduationPathId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
            Credits::of(20),
        );

        $path->updateProgress(Credits::of(5), 3.0);

        $this->assertSame(25.0, $path->completionPercentage());
        $this->assertFalse($path->isOnTrack());
    }

    public function test_update_progress_marks_off_track_when_gpa_below_2(): void
    {
        $path = GraduationPath::initialize(
            GraduationPathId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
            Credits::of(20),
        );

        $path->updateProgress(Credits::of(10), 1.5);

        $this->assertSame(50.0, $path->completionPercentage());
        $this->assertFalse($path->isOnTrack());
    }

    public function test_graduation_path_getters_return_correct_values(): void
    {
        $id = GraduationPathId::generate();
        $studentId = StudentId::generate();
        $curriculumId = CurriculumId::generate();
        $estimatedDate = new DateTimeImmutable('2028-06-30');
        $updatedAt = new DateTimeImmutable('2026-01-01');

        $path = GraduationPath::reconstitute(
            $id,
            $studentId,
            $curriculumId,
            Credits::of(10),
            Credits::of(20),
            50.0,
            true,
            $estimatedDate,
            $updatedAt,
        );

        $this->assertTrue($id->equals($path->id()));
        $this->assertTrue($studentId->equals($path->studentId()));
        $this->assertTrue($curriculumId->equals($path->curriculumId()));
        $this->assertSame(10, $path->creditsEarned()->value());
        $this->assertSame(20, $path->creditsRequired()->value());
        $this->assertSame(50.0, $path->completionPercentage());
        $this->assertTrue($path->isOnTrack());
        $this->assertSame($estimatedDate, $path->estimatedGraduationDate());
        $this->assertSame($updatedAt, $path->updatedAt());
    }
}
