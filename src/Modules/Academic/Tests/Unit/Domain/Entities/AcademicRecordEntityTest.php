<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\AcademicRecord;
use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\Events\CourseCompleted;
use Modules\Academic\Domain\ValueObjects\AcademicRecordId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Grade;
use PHPUnit\Framework\TestCase;

final class AcademicRecordEntityTest extends TestCase
{
    public function test_record_creates_with_grade(): void
    {
        $record = AcademicRecord::record(
            AcademicRecordId::generate(),
            EnrollmentId::generate(),
            'student-123',
            'user-123',
            'course-123',
            Grade::fromLetter(GradeLetter::A),
            'admin-123',
        );

        $this->assertSame('A', $record->grade()->letterValue());
        $this->assertSame(4.0, $record->grade()->gradePoints());
        $this->assertNotNull($record->recordedAt());

        $events = $record->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CourseCompleted::class, $events[0]);
    }

    public function test_record_can_be_reconstituted(): void
    {
        $record = AcademicRecord::reconstitute(
            AcademicRecordId::generate(),
            EnrollmentId::generate(),
            'student-123',
            'course-123',
            Grade::fromLetter(GradeLetter::BP),
            new DateTimeImmutable('2026-05-15'),
            'admin-123',
        );

        $this->assertSame('B+', $record->grade()->letterValue());
        $this->assertSame(3.3, $record->grade()->gradePoints());
        $this->assertSame('student-123', $record->studentId());
        $this->assertSame('course-123', $record->courseId());
    }

    public function test_grade_and_grade_points_return_correct_values(): void
    {
        $record = AcademicRecord::record(
            AcademicRecordId::generate(),
            EnrollmentId::generate(),
            'student-123',
            'user-123',
            'course-123',
            Grade::fromLetter(GradeLetter::C),
            'admin-123',
        );

        $this->assertSame('C', $record->grade()->letterValue());
        $this->assertSame(2.0, $record->grade()->gradePoints());
    }

    public function test_is_passing_checks_if_grade_is_passing(): void
    {
        $passing = AcademicRecord::record(
            AcademicRecordId::generate(),
            EnrollmentId::generate(),
            'student-123',
            'user-123',
            'course-123',
            Grade::fromLetter(GradeLetter::C),
            'admin-123',
        );

        $failing = AcademicRecord::record(
            AcademicRecordId::generate(),
            EnrollmentId::generate(),
            'student-123',
            'user-123',
            'course-123',
            Grade::fromLetter(GradeLetter::F),
            'admin-123',
        );

        $this->assertTrue($passing->grade()->isPassing());
        $this->assertFalse($failing->grade()->isPassing());
    }

    public function test_record_getters_return_correct_values(): void
    {
        $id = AcademicRecordId::generate();
        $enrollmentId = EnrollmentId::generate();
        $grade = Grade::fromLetter(GradeLetter::A);
        $recordedAt = new DateTimeImmutable('2026-05-15');

        $record = AcademicRecord::reconstitute(
            $id,
            $enrollmentId,
            'student-123',
            'course-123',
            $grade,
            $recordedAt,
            'admin-123',
        );

        $this->assertTrue($id->equals($record->id()));
        $this->assertTrue($enrollmentId->equals($record->enrollmentId()));
        $this->assertSame('student-123', $record->studentId());
        $this->assertSame('course-123', $record->courseId());
        $this->assertTrue($grade->equals($record->grade()));
        $this->assertSame($recordedAt, $record->recordedAt());
        $this->assertSame('admin-123', $record->recordedByUserId());
    }
}
