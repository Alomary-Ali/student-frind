<?php

declare(strict_types=1);

namespace Modules\Academic\Application\Mappers;

use Modules\Academic\Application\DTOs\AcademicAlertDto;
use Modules\Academic\Application\DTOs\CourseDto;
use Modules\Academic\Application\DTOs\EnrollmentDto;
use Modules\Academic\Application\DTOs\StudentDto;
use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Entities\Student;

final class AcademicMapper
{
    public function toStudentDto(Student $student): StudentDto
    {
        return new StudentDto(
            id: $student->id()->value(),
            userId: $student->userId(),
            studentNumber: $student->studentNumber(),
            academicStatus: $student->academicStatus()->value,
            academicStanding: $student->academicStanding()->value,
            cumulativeGpa: $student->cumulativeGpa()->value(),
            semesterGpa: $student->semesterGpa()?->value(),
            currentSemesterId: $student->currentSemesterId(),
            institutionId: $student->institutionId(),
            universityId: $student->universityId(),
            collegeId: $student->collegeId(),
            departmentId: $student->departmentId(),
            majorId: $student->majorId(),
            level: $student->level(),
            createdAt: $student->createdAt()->format('c'),
        );
    }

    public function toCourseDto(Course $course): CourseDto
    {
        return new CourseDto(
            id: $course->id()->value(),
            code: $course->code(),
            title: $course->title(),
            description: $course->description(),
            creditHours: $course->creditHours()->value(),
            isActive: $course->isActive(),
            institutionId: $course->institutionId(),
        );
    }

    public function toEnrollmentDto(Enrollment $enrollment): EnrollmentDto
    {
        return new EnrollmentDto(
            id: $enrollment->id()->value(),
            studentId: $enrollment->studentId()->value(),
            courseId: $enrollment->courseId()->value(),
            semesterId: $enrollment->semesterId()->value(),
            status: $enrollment->status()->value,
            enrolledAt: $enrollment->enrolledAt()->format('c'),
        );
    }

    public function toAcademicAlertDto(AcademicAlert $alert): AcademicAlertDto
    {
        return new AcademicAlertDto(
            id: $alert->id()->value(),
            studentId: $alert->studentId()->value(),
            alertType: $alert->alertType()->value,
            severity: $alert->severity()->value,
            message: $alert->message(),
            metadata: $alert->metadata(),
            isResolved: $alert->isResolved(),
            createdAt: $alert->createdAt()->format('c'),
            resolvedAt: $alert->resolvedAt()?->format('c'),
            resolvedBy: $alert->resolvedBy(),
        );
    }
}
