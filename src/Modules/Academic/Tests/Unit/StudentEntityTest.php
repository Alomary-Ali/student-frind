<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\AcademicStatus;
use Modules\Academic\Domain\Events\GpaUpdated;
use Modules\Academic\Domain\Events\StudentCreated;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class StudentEntityTest extends TestCase
{
    public function test_student_can_be_created(): void
    {
        $student = Student::create(
            StudentId::generate(),
            'user-uuid',
            'STU-2026-001',
        );

        $this->assertSame(AcademicStatus::Active, $student->academicStatus());
        $this->assertSame(AcademicStanding::GoodStanding, $student->academicStanding());
        $this->assertSame(0.0, $student->cumulativeGpa()->value());

        $events = $student->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(StudentCreated::class, $events[0]);
    }

    public function test_student_gpa_update_raises_event(): void
    {
        $student = Student::create(StudentId::generate(), 'user-uuid', 'STU-001');
        $student->releaseEvents();

        $student->updateGpa(Gpa::of(3.5));

        $this->assertSame(3.5, $student->cumulativeGpa()->value());
        $this->assertSame(AcademicStanding::GoodStanding, $student->academicStanding());

        $events = $student->releaseEvents();
        $this->assertInstanceOf(GpaUpdated::class, $events[0]);
    }
}
