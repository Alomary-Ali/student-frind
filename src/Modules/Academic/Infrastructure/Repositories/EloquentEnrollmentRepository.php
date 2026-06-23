<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentEnrollment;

final class EloquentEnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function findById(EnrollmentId $id): ?Enrollment
    {
        $model = EloquentEnrollment::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function existsForStudentCourseSemester(
        StudentId $studentId,
        CourseId $courseId,
        SemesterId $semesterId,
    ): bool {
        return EloquentEnrollment::where('student_id', $studentId->value())
            ->where('course_id', $courseId->value())
            ->where('semester_id', $semesterId->value())
            ->where('status', '!=', EnrollmentStatus::Dropped->value)
            ->exists();
    }

    public function save(Enrollment $enrollment): void
    {
        EloquentEnrollment::updateOrCreate(
            ['id' => $enrollment->id()->value()],
            [
                'student_id' => $enrollment->studentId()->value(),
                'course_id' => $enrollment->courseId()->value(),
                'semester_id' => $enrollment->semesterId()->value(),
                'status' => $enrollment->status()->value,
                'enrolled_at' => $enrollment->enrolledAt()->format('Y-m-d H:i:s'),
            ],
        );
    }

    public function findByStudentId(StudentId $studentId): array
    {
        return EloquentEnrollment::where('student_id', $studentId->value())
            ->orderByDesc('enrolled_at')
            ->get()
            ->map(fn ($m) => $this->toDomain($m))
            ->all();
    }

    public function findCompletedByStudent(StudentId $studentId): array
    {
        return EloquentEnrollment::where('student_id', $studentId->value())
            ->where('status', 'completed')
            ->orderByDesc('enrolled_at')
            ->get()
            ->map(fn ($m) => $this->toDomain($m))
            ->all();
    }

    private function toDomain(EloquentEnrollment $model): Enrollment
    {
        $enrolledAt = $model->enrolled_at
            ? new DateTimeImmutable($model->enrolled_at->format('Y-m-d H:i:s'))
            : new DateTimeImmutable;

        return Enrollment::reconstitute(
            id: EnrollmentId::fromString($model->id),
            studentId: StudentId::fromString($model->student_id),
            courseId: CourseId::fromString($model->course_id),
            semesterId: SemesterId::fromString($model->semester_id),
            status: EnrollmentStatus::from($model->status),
            enrolledAt: $enrolledAt,
        );
    }
}
