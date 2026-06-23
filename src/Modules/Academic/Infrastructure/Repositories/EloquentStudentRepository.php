<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\AcademicStatus;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentEnrollment;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;

final class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function findById(StudentId $id): ?Student
    {
        $model = EloquentStudent::with([
            'enrollments.course',
            'enrollments.semester',
            'enrollments.academicRecord',
        ])->find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(string $userId): ?Student
    {
        $model = EloquentStudent::with([
            'enrollments.course',
            'enrollments.semester',
            'enrollments.academicRecord',
        ])->where('user_id', $userId)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function existsByUserId(string $userId): bool
    {
        return EloquentStudent::where('user_id', $userId)->exists();
    }

    public function save(Student $student): void
    {
        EloquentStudent::updateOrCreate(
            ['id' => $student->id()->value()],
            [
                'user_id' => $student->userId(),
                'student_number' => $student->studentNumber(),
                'academic_status' => $student->academicStatus()->value,
                'academic_standing' => $student->academicStanding()->value,
                'cumulative_gpa' => $student->cumulativeGpa()->value(),
                'semester_gpa' => $student->semesterGpa()?->value(),
                'current_semester_id' => $student->currentSemesterId(),
                'institution_id' => $student->institutionId(),
                'university_id' => $student->universityId(),
                'college_id' => $student->collegeId(),
                'department_id' => $student->departmentId(),
                'major_id' => $student->majorId(),
                'level' => $student->level(),
            ],
        );

        foreach ($student->enrollments() as $enrollment) {
            EloquentEnrollment::updateOrCreate(
                ['id' => $enrollment->id()->value()],
                [
                    'student_id' => $enrollment->studentId()->value(),
                    'course_id' => $enrollment->courseId()->value(),
                    'semester_id' => $enrollment->semesterId()->value(),
                    'status' => $enrollment->status()->value,
                    'enrolled_at' => $enrollment->enrolledAt(),
                ],
            );
        }
    }

    private function toDomain(EloquentStudent $model): Student
    {
        $enrollments = [];

        foreach ($model->enrollments as $enrollmentModel) {
            $enrolledAt = $enrollmentModel->enrolled_at
                ? new DateTimeImmutable($enrollmentModel->enrolled_at->format('Y-m-d H:i:s'))
                : new DateTimeImmutable;

            $enrollments[] = Enrollment::reconstitute(
                id: EnrollmentId::fromString($enrollmentModel->id),
                studentId: StudentId::fromString($enrollmentModel->student_id),
                courseId: CourseId::fromString($enrollmentModel->course_id),
                semesterId: SemesterId::fromString($enrollmentModel->semester_id),
                status: EnrollmentStatus::from($enrollmentModel->status),
                enrolledAt: $enrolledAt,
            );
        }

        $createdAt = $model->created_at
            ? new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s'))
            : new DateTimeImmutable;

        return Student::reconstitute(
            id: StudentId::fromString($model->id),
            userId: $model->user_id,
            studentNumber: $model->student_number,
            academicStatus: AcademicStatus::from($model->academic_status),
            academicStanding: AcademicStanding::from($model->academic_standing),
            cumulativeGpa: Gpa::of((float) $model->cumulative_gpa),
            semesterGpa: $model->semester_gpa ? Gpa::of((float) $model->semester_gpa) : null,
            currentSemesterId: $model->current_semester_id,
            institutionId: $model->institution_id,
            universityId: $model->university_id,
            collegeId: $model->college_id,
            departmentId: $model->department_id,
            majorId: $model->major_id,
            level: $model->level ?? '1',
            createdAt: $createdAt,
            enrollments: $enrollments,
        );
    }
}
