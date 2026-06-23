<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\Mappers;

use DateTimeImmutable;
use Modules\Academic\Application\DTOs\AcademicAlertDto;
use Modules\Academic\Application\DTOs\CourseDto;
use Modules\Academic\Application\DTOs\EnrollmentDto;
use Modules\Academic\Application\DTOs\StudentDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\AcademicStatus;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class AcademicMapperTest extends TestCase
{
    private AcademicMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new AcademicMapper;
    }

    public function test_to_student_dto_maps_all_fields(): void
    {
        $createdAt = new DateTimeImmutable('2026-01-15T10:00:00+00:00');
        $studentId = StudentId::fromString('550e8400-e29b-41d4-a716-446655440001');

        $student = Student::reconstitute(
            id: $studentId,
            userId: 'user-uuid',
            studentNumber: '2024001',
            academicStatus: AcademicStatus::Active,
            academicStanding: AcademicStanding::GoodStanding,
            cumulativeGpa: Gpa::of(3.75),
            semesterGpa: Gpa::of(3.5),
            currentSemesterId: 'sem-uuid',
            institutionId: 'inst-uuid',
            universityId: 'uni-uuid',
            collegeId: 'college-uuid',
            departmentId: 'dept-uuid',
            majorId: 'major-uuid',
            level: '2',
            createdAt: $createdAt,
        );

        $dto = $this->mapper->toStudentDto($student);

        $this->assertInstanceOf(StudentDto::class, $dto);
        $this->assertSame($studentId->value(), $dto->id);
        $this->assertSame('user-uuid', $dto->userId);
        $this->assertSame('2024001', $dto->studentNumber);
        $this->assertSame('active', $dto->academicStatus);
        $this->assertSame('good_standing', $dto->academicStanding);
        $this->assertSame(3.75, $dto->cumulativeGpa);
        $this->assertSame(3.5, $dto->semesterGpa);
        $this->assertSame('sem-uuid', $dto->currentSemesterId);
        $this->assertSame('inst-uuid', $dto->institutionId);
        $this->assertSame('uni-uuid', $dto->universityId);
        $this->assertSame('college-uuid', $dto->collegeId);
        $this->assertSame('dept-uuid', $dto->departmentId);
        $this->assertSame('major-uuid', $dto->majorId);
        $this->assertSame('2', $dto->level);
        $this->assertSame('2026-01-15T10:00:00+00:00', $dto->createdAt);
    }

    public function test_to_student_dto_maps_nullable_fields(): void
    {
        $createdAt = new DateTimeImmutable('2026-01-15T10:00:00+00:00');

        $student = Student::reconstitute(
            id: StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            userId: 'user-uuid',
            studentNumber: '2024001',
            academicStatus: AcademicStatus::Active,
            academicStanding: AcademicStanding::GoodStanding,
            cumulativeGpa: Gpa::zero(),
            semesterGpa: null,
            currentSemesterId: null,
            institutionId: null,
            universityId: null,
            collegeId: null,
            departmentId: null,
            majorId: null,
            level: '1',
            createdAt: $createdAt,
        );

        $dto = $this->mapper->toStudentDto($student);

        $this->assertNull($dto->semesterGpa);
        $this->assertNull($dto->currentSemesterId);
        $this->assertNull($dto->institutionId);
        $this->assertNull($dto->universityId);
        $this->assertNull($dto->collegeId);
        $this->assertNull($dto->departmentId);
        $this->assertNull($dto->majorId);
    }

    public function test_to_course_dto_maps_all_fields(): void
    {
        $createdAt = new DateTimeImmutable('2026-01-15T10:00:00+00:00');
        $courseId = CourseId::fromString('660e8400-e29b-41d4-a716-446655440002');

        $course = Course::reconstitute(
            id: $courseId,
            code: 'CS101',
            title: 'Introduction to CS',
            description: 'Fundamentals of computer science',
            creditHours: Credits::of(3),
            isActive: true,
            institutionId: 'inst-uuid',
            createdAt: $createdAt,
        );

        $dto = $this->mapper->toCourseDto($course);

        $this->assertInstanceOf(CourseDto::class, $dto);
        $this->assertSame($courseId->value(), $dto->id);
        $this->assertSame('CS101', $dto->code);
        $this->assertSame('Introduction to CS', $dto->title);
        $this->assertSame('Fundamentals of computer science', $dto->description);
        $this->assertSame(3, $dto->creditHours);
        $this->assertTrue($dto->isActive);
        $this->assertSame('inst-uuid', $dto->institutionId);
    }

    public function test_to_course_dto_inactive(): void
    {
        $createdAt = new DateTimeImmutable('2026-01-15T10:00:00+00:00');

        $course = Course::reconstitute(
            id: CourseId::fromString('660e8400-e29b-41d4-a716-446655440002'),
            code: 'MATH201',
            title: 'Calculus II',
            description: 'Advanced calculus',
            creditHours: Credits::of(4),
            isActive: false,
            institutionId: null,
            createdAt: $createdAt,
        );

        $dto = $this->mapper->toCourseDto($course);

        $this->assertFalse($dto->isActive);
        $this->assertNull($dto->institutionId);
    }

    public function test_to_enrollment_dto_maps_all_fields(): void
    {
        $enrolledAt = new DateTimeImmutable('2026-02-01T08:00:00+00:00');

        $enrollment = Enrollment::reconstitute(
            id: EnrollmentId::fromString('770e8400-e29b-41d4-a716-446655440003'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            courseId: CourseId::fromString('660e8400-e29b-41d4-a716-446655440002'),
            semesterId: SemesterId::fromString('880e8400-e29b-41d4-a716-446655440004'),
            status: EnrollmentStatus::Enrolled,
            enrolledAt: $enrolledAt,
        );

        $dto = $this->mapper->toEnrollmentDto($enrollment);

        $this->assertInstanceOf(EnrollmentDto::class, $dto);
        $this->assertSame('770e8400-e29b-41d4-a716-446655440003', $dto->id);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440001', $dto->studentId);
        $this->assertSame('660e8400-e29b-41d4-a716-446655440002', $dto->courseId);
        $this->assertSame('880e8400-e29b-41d4-a716-446655440004', $dto->semesterId);
        $this->assertSame('enrolled', $dto->status);
        $this->assertSame('2026-02-01T08:00:00+00:00', $dto->enrolledAt);
    }

    public function test_to_enrollment_dto_completed_status(): void
    {
        $enrolledAt = new DateTimeImmutable('2026-02-01T08:00:00+00:00');

        $enrollment = Enrollment::reconstitute(
            id: EnrollmentId::fromString('770e8400-e29b-41d4-a716-446655440003'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            courseId: CourseId::fromString('660e8400-e29b-41d4-a716-446655440002'),
            semesterId: SemesterId::fromString('880e8400-e29b-41d4-a716-446655440004'),
            status: EnrollmentStatus::Completed,
            enrolledAt: $enrolledAt,
        );

        $dto = $this->mapper->toEnrollmentDto($enrollment);

        $this->assertSame('completed', $dto->status);
    }

    public function test_to_academic_alert_dto_maps_all_fields(): void
    {
        $createdAt = new DateTimeImmutable('2026-03-01T10:00:00+00:00');
        $resolvedAt = new DateTimeImmutable('2026-03-05T14:00:00+00:00');

        $alert = AcademicAlert::reconstitute(
            id: AlertId::fromString('990e8400-e29b-41d4-a716-446655440005'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            alertType: AlertType::LowGpa,
            severity: AlertSeverity::High,
            message: 'GPA dropped below 2.0',
            metadata: ['gpa' => 1.8],
            isResolved: true,
            createdAt: $createdAt,
            resolvedAt: $resolvedAt,
            resolvedBy: 'admin-uuid',
        );

        $dto = $this->mapper->toAcademicAlertDto($alert);

        $this->assertInstanceOf(AcademicAlertDto::class, $dto);
        $this->assertSame('990e8400-e29b-41d4-a716-446655440005', $dto->id);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440001', $dto->studentId);
        $this->assertSame('low_gpa', $dto->alertType);
        $this->assertSame('high', $dto->severity);
        $this->assertSame('GPA dropped below 2.0', $dto->message);
        $this->assertSame(['gpa' => 1.8], $dto->metadata);
        $this->assertTrue($dto->isResolved);
        $this->assertSame('2026-03-05T14:00:00+00:00', $dto->resolvedAt);
        $this->assertSame('admin-uuid', $dto->resolvedBy);
    }

    public function test_to_academic_alert_dto_unresolved(): void
    {
        $createdAt = new DateTimeImmutable('2026-03-01T10:00:00+00:00');

        $alert = AcademicAlert::reconstitute(
            id: AlertId::fromString('990e8400-e29b-41d4-a716-446655440005'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            alertType: AlertType::CreditDeficit,
            severity: AlertSeverity::Medium,
            message: 'Credit deficit alert',
            metadata: null,
            isResolved: false,
            createdAt: $createdAt,
            resolvedAt: null,
            resolvedBy: null,
        );

        $dto = $this->mapper->toAcademicAlertDto($alert);

        $this->assertFalse($dto->isResolved);
        $this->assertNull($dto->resolvedAt);
        $this->assertNull($dto->resolvedBy);
        $this->assertNull($dto->metadata);
    }
}
