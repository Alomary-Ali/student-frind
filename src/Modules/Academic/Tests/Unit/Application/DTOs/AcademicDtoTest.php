<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\DTOs;

use Modules\Academic\Application\DTOs\AcademicAlertDto;
use Modules\Academic\Application\DTOs\AssignAcademicPlanDto;
use Modules\Academic\Application\DTOs\CourseDto;
use Modules\Academic\Application\DTOs\CreateCourseDto;
use Modules\Academic\Application\DTOs\CreateStudentDto;
use Modules\Academic\Application\DTOs\EnrollmentDto;
use Modules\Academic\Application\DTOs\EnrollStudentDto;
use Modules\Academic\Application\DTOs\RecordGradeDto;
use Modules\Academic\Application\DTOs\StudentDto;
use PHPUnit\Framework\TestCase;

final class AcademicDtoTest extends TestCase
{
    public function test_create_student_dto_construction_and_access(): void
    {
        $dto = new CreateStudentDto(
            userId: 'user-uuid',
            studentNumber: '2024001',
            institutionId: 'inst-uuid',
            universityId: 'uni-uuid',
            collegeId: 'college-uuid',
            departmentId: 'dept-uuid',
            majorId: 'major-uuid',
            level: '2',
            semesterGpa: 3.5,
            currentSemesterId: 'sem-uuid',
        );

        $this->assertSame('user-uuid', $dto->userId);
        $this->assertSame('2024001', $dto->studentNumber);
        $this->assertSame('inst-uuid', $dto->institutionId);
        $this->assertSame('uni-uuid', $dto->universityId);
        $this->assertSame('college-uuid', $dto->collegeId);
        $this->assertSame('dept-uuid', $dto->departmentId);
        $this->assertSame('major-uuid', $dto->majorId);
        $this->assertSame('2', $dto->level);
        $this->assertSame(3.5, $dto->semesterGpa);
        $this->assertSame('sem-uuid', $dto->currentSemesterId);
    }

    public function test_create_student_dto_with_defaults(): void
    {
        $dto = new CreateStudentDto(
            userId: 'user-uuid',
            studentNumber: '2024001',
        );

        $this->assertNull($dto->institutionId);
        $this->assertNull($dto->universityId);
        $this->assertNull($dto->collegeId);
        $this->assertNull($dto->departmentId);
        $this->assertNull($dto->majorId);
        $this->assertSame('1', $dto->level);
        $this->assertNull($dto->semesterGpa);
        $this->assertNull($dto->currentSemesterId);
    }

    public function test_student_dto_construction_and_access(): void
    {
        $dto = new StudentDto(
            id: 'student-uuid',
            userId: 'user-uuid',
            studentNumber: '2024001',
            academicStatus: 'active',
            academicStanding: 'good_standing',
            cumulativeGpa: 3.75,
            semesterGpa: 3.5,
            currentSemesterId: 'sem-uuid',
            institutionId: 'inst-uuid',
            universityId: 'uni-uuid',
            collegeId: 'college-uuid',
            departmentId: 'dept-uuid',
            majorId: 'major-uuid',
            level: '3',
            createdAt: '2026-01-15T00:00:00+00:00',
        );

        $this->assertSame('student-uuid', $dto->id);
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
        $this->assertSame('3', $dto->level);
        $this->assertSame('2026-01-15T00:00:00+00:00', $dto->createdAt);
    }

    public function test_student_dto_nullable_fields(): void
    {
        $dto = new StudentDto(
            id: 'student-uuid',
            userId: 'user-uuid',
            studentNumber: '2024001',
            academicStatus: 'active',
            academicStanding: 'good_standing',
            cumulativeGpa: 0.0,
            semesterGpa: null,
            currentSemesterId: null,
            institutionId: null,
            universityId: null,
            collegeId: null,
            departmentId: null,
            majorId: null,
            level: '1',
            createdAt: '2026-01-15T00:00:00+00:00',
        );

        $this->assertNull($dto->semesterGpa);
        $this->assertNull($dto->currentSemesterId);
        $this->assertNull($dto->institutionId);
        $this->assertNull($dto->universityId);
        $this->assertNull($dto->collegeId);
        $this->assertNull($dto->departmentId);
        $this->assertNull($dto->majorId);
    }

    public function test_create_course_dto_construction_and_access(): void
    {
        $dto = new CreateCourseDto(
            code: 'CS101',
            title: 'Intro to CS',
            description: 'Fundamentals',
            creditHours: 3,
            institutionId: 'inst-uuid',
        );

        $this->assertSame('CS101', $dto->code);
        $this->assertSame('Intro to CS', $dto->title);
        $this->assertSame('Fundamentals', $dto->description);
        $this->assertSame(3, $dto->creditHours);
        $this->assertSame('inst-uuid', $dto->institutionId);
    }

    public function test_create_course_dto_without_institution(): void
    {
        $dto = new CreateCourseDto(
            code: 'MATH201',
            title: 'Calculus II',
            description: 'Advanced',
            creditHours: 4,
        );

        $this->assertNull($dto->institutionId);
    }

    public function test_course_dto_construction_and_access(): void
    {
        $dto = new CourseDto(
            id: 'course-uuid',
            code: 'CS101',
            title: 'Intro to CS',
            description: 'Fundamentals',
            creditHours: 3,
            isActive: true,
            institutionId: 'inst-uuid',
        );

        $this->assertSame('course-uuid', $dto->id);
        $this->assertSame('CS101', $dto->code);
        $this->assertSame('Intro to CS', $dto->title);
        $this->assertSame('Fundamentals', $dto->description);
        $this->assertSame(3, $dto->creditHours);
        $this->assertTrue($dto->isActive);
        $this->assertSame('inst-uuid', $dto->institutionId);
    }

    public function test_course_dto_inactive(): void
    {
        $dto = new CourseDto(
            id: 'course-uuid',
            code: 'CS101',
            title: 'Intro',
            description: 'Desc',
            creditHours: 3,
            isActive: false,
            institutionId: null,
        );

        $this->assertFalse($dto->isActive);
        $this->assertNull($dto->institutionId);
    }

    public function test_enroll_student_dto_construction_and_access(): void
    {
        $dto = new EnrollStudentDto(
            studentId: 'student-uuid',
            courseId: 'course-uuid',
            semesterId: 'sem-uuid',
            actorUserId: 'actor-uuid',
        );

        $this->assertSame('student-uuid', $dto->studentId);
        $this->assertSame('course-uuid', $dto->courseId);
        $this->assertSame('sem-uuid', $dto->semesterId);
        $this->assertSame('actor-uuid', $dto->actorUserId);
    }

    public function test_enrollment_dto_construction_and_access(): void
    {
        $dto = new EnrollmentDto(
            id: 'enroll-uuid',
            studentId: 'student-uuid',
            courseId: 'course-uuid',
            semesterId: 'sem-uuid',
            status: 'enrolled',
            enrolledAt: '2026-01-15T00:00:00+00:00',
        );

        $this->assertSame('enroll-uuid', $dto->id);
        $this->assertSame('student-uuid', $dto->studentId);
        $this->assertSame('course-uuid', $dto->courseId);
        $this->assertSame('sem-uuid', $dto->semesterId);
        $this->assertSame('enrolled', $dto->status);
        $this->assertSame('2026-01-15T00:00:00+00:00', $dto->enrolledAt);
    }

    public function test_record_grade_dto_construction_and_access(): void
    {
        $dto = new RecordGradeDto(
            enrollmentId: 'enroll-uuid',
            gradeLetter: 'A',
            recordedByUserId: 'actor-uuid',
        );

        $this->assertSame('enroll-uuid', $dto->enrollmentId);
        $this->assertSame('A', $dto->gradeLetter);
        $this->assertSame('actor-uuid', $dto->recordedByUserId);
    }

    public function test_academic_alert_dto_construction_and_access(): void
    {
        $dto = new AcademicAlertDto(
            id: 'alert-uuid',
            studentId: 'student-uuid',
            alertType: 'low_gpa',
            severity: 'high',
            message: 'Your GPA has dropped below 2.0',
            metadata: ['gpa' => 1.5],
            isResolved: false,
            createdAt: '2026-03-01T00:00:00+00:00',
            resolvedAt: null,
            resolvedBy: null,
        );

        $this->assertSame('alert-uuid', $dto->id);
        $this->assertSame('student-uuid', $dto->studentId);
        $this->assertSame('low_gpa', $dto->alertType);
        $this->assertSame('high', $dto->severity);
        $this->assertSame('Your GPA has dropped below 2.0', $dto->message);
        $this->assertSame(['gpa' => 1.5], $dto->metadata);
        $this->assertFalse($dto->isResolved);
        $this->assertNull($dto->resolvedAt);
        $this->assertNull($dto->resolvedBy);
    }

    public function test_academic_alert_dto_resolved(): void
    {
        $dto = new AcademicAlertDto(
            id: 'alert-uuid',
            studentId: 'student-uuid',
            alertType: 'low_gpa',
            severity: 'high',
            message: 'Alert message',
            metadata: null,
            isResolved: true,
            createdAt: '2026-03-01T00:00:00+00:00',
            resolvedAt: '2026-03-05T00:00:00+00:00',
            resolvedBy: 'admin-uuid',
        );

        $this->assertTrue($dto->isResolved);
        $this->assertSame('2026-03-05T00:00:00+00:00', $dto->resolvedAt);
        $this->assertSame('admin-uuid', $dto->resolvedBy);
    }

    public function test_assign_academic_plan_dto_construction_and_access(): void
    {
        $dto = new AssignAcademicPlanDto(
            studentId: 'student-uuid',
            curriculumId: 'curriculum-uuid',
            actorUserId: 'actor-uuid',
            institutionId: 'inst-uuid',
            estimatedGraduationDate: '2029-06-15',
        );

        $this->assertSame('student-uuid', $dto->studentId);
        $this->assertSame('curriculum-uuid', $dto->curriculumId);
        $this->assertSame('actor-uuid', $dto->actorUserId);
        $this->assertSame('inst-uuid', $dto->institutionId);
        $this->assertSame('2029-06-15', $dto->estimatedGraduationDate);
    }

    public function test_assign_academic_plan_dto_with_defaults(): void
    {
        $dto = new AssignAcademicPlanDto(
            studentId: 'student-uuid',
            curriculumId: 'curriculum-uuid',
            actorUserId: 'actor-uuid',
        );

        $this->assertNull($dto->institutionId);
        $this->assertNull($dto->estimatedGraduationDate);
    }
}
