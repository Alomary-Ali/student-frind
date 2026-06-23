<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\AcademicRecordRepositoryInterface;
use Modules\Academic\Domain\Entities\AcademicRecord;
use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\ValueObjects\AcademicRecordId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Grade;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicRecord;

final class EloquentAcademicRecordRepository implements AcademicRecordRepositoryInterface
{
    public function save(AcademicRecord $record): void
    {
        EloquentAcademicRecord::updateOrCreate(
            ['id' => $record->id()->value()],
            [
                'enrollment_id' => $record->enrollmentId()->value(),
                'student_id' => $record->studentId(),
                'course_id' => $record->courseId(),
                'grade_letter' => $record->grade()->letterValue(),
                'grade_points' => $record->grade()->gradePoints(),
                'recorded_at' => $record->recordedAt(),
                'recorded_by_user_id' => $record->recordedByUserId(),
            ],
        );
    }

    public function findByEnrollmentId(EnrollmentId $enrollmentId): ?AcademicRecord
    {
        $model = EloquentAcademicRecord::where('enrollment_id', $enrollmentId->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findGradedRecordsByStudentId(StudentId $studentId): array
    {
        return EloquentAcademicRecord::where('academic_records.student_id', $studentId->value())
            ->join('academic_enrollments', 'academic_records.enrollment_id', '=', 'academic_enrollments.id')
            ->join('academic_courses', 'academic_records.course_id', '=', 'academic_courses.id')
            ->select([
                'academic_records.grade_points',
                'academic_courses.credit_hours',
                'academic_enrollments.semester_id',
            ])
            ->get()
            ->map(fn ($row) => [
                'grade_points' => (float) $row->grade_points,
                'credit_hours' => (int) $row->credit_hours,
                'semester_id' => $row->semester_id,
            ])
            ->all();
    }

    private function toDomain(EloquentAcademicRecord $model): AcademicRecord
    {
        return AcademicRecord::reconstitute(
            id: AcademicRecordId::fromString($model->id),
            enrollmentId: EnrollmentId::fromString($model->enrollment_id),
            studentId: $model->student_id,
            courseId: $model->course_id,
            grade: Grade::fromLetter(GradeLetter::from($model->grade_letter)),
            recordedAt: new DateTimeImmutable($model->recorded_at->toIso8601String()),
            recordedByUserId: $model->recorded_by_user_id,
        );
    }
}
