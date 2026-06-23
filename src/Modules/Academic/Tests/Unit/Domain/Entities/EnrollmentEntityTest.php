<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\Events\StudentEnrolled;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class EnrollmentEntityTest extends TestCase
{
    public function test_enrollment_can_be_created(): void
    {
        $enrollment = Enrollment::create(
            EnrollmentId::generate(),
            StudentId::generate(),
            'user-123',
            CourseId::generate(),
            SemesterId::generate(),
        );

        $this->assertSame(EnrollmentStatus::Enrolled, $enrollment->status());
        $this->assertFalse($enrollment->isCompleted());
        $this->assertInstanceOf(DateTimeImmutable::class, $enrollment->enrolledAt());

        $events = $enrollment->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(StudentEnrolled::class, $events[0]);
    }

    public function test_enrollment_can_be_reconstituted(): void
    {
        $enrollment = Enrollment::reconstitute(
            EnrollmentId::generate(),
            StudentId::generate(),
            CourseId::generate(),
            SemesterId::generate(),
            EnrollmentStatus::Enrolled,
            new DateTimeImmutable('2026-01-15'),
        );

        $this->assertSame(EnrollmentStatus::Enrolled, $enrollment->status());
        $this->assertNotNull($enrollment->enrolledAt());
        $this->assertCount(0, $enrollment->releaseEvents());
    }

    public function test_complete_changes_status_to_completed(): void
    {
        $enrollment = Enrollment::create(
            EnrollmentId::generate(),
            StudentId::generate(),
            'user-123',
            CourseId::generate(),
            SemesterId::generate(),
        );
        $enrollment->releaseEvents();

        $enrollment->complete();

        $this->assertTrue($enrollment->isCompleted());
        $this->assertSame(EnrollmentStatus::Completed, $enrollment->status());
    }

    public function test_drop_changes_status_to_dropped(): void
    {
        $enrollment = Enrollment::create(
            EnrollmentId::generate(),
            StudentId::generate(),
            'user-123',
            CourseId::generate(),
            SemesterId::generate(),
        );
        $enrollment->releaseEvents();

        $enrollment->drop();

        $this->assertSame(EnrollmentStatus::Dropped, $enrollment->status());
        $this->assertFalse($enrollment->isCompleted());
    }

    public function test_is_active_for_checks_course_and_semester_match(): void
    {
        $courseId = CourseId::generate();
        $semesterId = SemesterId::generate();

        $enrollment = Enrollment::create(
            EnrollmentId::generate(),
            StudentId::generate(),
            'user-123',
            $courseId,
            $semesterId,
        );
        $enrollment->releaseEvents();

        $this->assertTrue($enrollment->isActiveFor($courseId, $semesterId));
        $this->assertFalse($enrollment->isActiveFor(CourseId::generate(), $semesterId));
        $this->assertFalse($enrollment->isActiveFor($courseId, SemesterId::generate()));
    }

    public function test_is_active_for_returns_false_when_dropped(): void
    {
        $courseId = CourseId::generate();
        $semesterId = SemesterId::generate();

        $enrollment = Enrollment::create(
            EnrollmentId::generate(),
            StudentId::generate(),
            'user-123',
            $courseId,
            $semesterId,
        );
        $enrollment->releaseEvents();
        $enrollment->drop();

        $this->assertFalse($enrollment->isActiveFor($courseId, $semesterId));
    }

    public function test_enrollment_getters_return_correct_values(): void
    {
        $id = EnrollmentId::generate();
        $studentId = StudentId::generate();
        $courseId = CourseId::generate();
        $semesterId = SemesterId::generate();
        $enrolledAt = new DateTimeImmutable('2026-01-15');

        $enrollment = Enrollment::reconstitute(
            $id,
            $studentId,
            $courseId,
            $semesterId,
            EnrollmentStatus::Enrolled,
            $enrolledAt,
        );

        $this->assertTrue($id->equals($enrollment->id()));
        $this->assertTrue($studentId->equals($enrollment->studentId()));
        $this->assertTrue($courseId->equals($enrollment->courseId()));
        $this->assertTrue($semesterId->equals($enrollment->semesterId()));
        $this->assertSame(EnrollmentStatus::Enrolled, $enrollment->status());
        $this->assertSame($enrolledAt, $enrollment->enrolledAt());
    }
}
