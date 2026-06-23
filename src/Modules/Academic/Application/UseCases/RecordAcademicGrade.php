<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Application\DTOs\RecordGradeDto;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\AcademicRecordRepositoryInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Entities\AcademicRecord;
use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\Exceptions\EnrollmentNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\Services\GpaCalculationService;
use Modules\Academic\Domain\ValueObjects\AcademicRecordId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Grade;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class RecordAcademicGrade
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private EnrollmentRepositoryInterface $enrollments,
        private CourseRepositoryInterface $courses,
        private AcademicRecordRepositoryInterface $records,
        private GraduationPathRepositoryInterface $graduationPaths,
        private TransactionManagerInterface $transactions,
        private EventDispatcherInterface $events,
        private AcademicAuditLoggerInterface $audit,
        private GpaCalculationService $gpaService,
    ) {}

    public function execute(RecordGradeDto $dto): array
    {
        return $this->transactions->runInTransaction(function () use ($dto) {
            $enrollmentId = EnrollmentId::fromString($dto->enrollmentId);
            $enrollment = $this->enrollments->findById($enrollmentId)
                ?? throw EnrollmentNotFoundException::forId($dto->enrollmentId);

            $student = $this->students->findById($enrollment->studentId())
                ?? throw StudentNotFoundException::forId($enrollment->studentId()->value());

            $course = $this->courses->findById($enrollment->courseId());
            $grade = Grade::fromLetter(GradeLetter::from($dto->gradeLetter));

            $enrollment->complete();
            $this->enrollments->save($enrollment);

            $record = AcademicRecord::record(
                id: AcademicRecordId::generate(),
                enrollmentId: $enrollmentId,
                studentId: $student->id()->value(),
                userId: $student->userId(),
                courseId: $enrollment->courseId()->value(),
                grade: $grade,
                recordedByUserId: $dto->recordedByUserId,
            );

            $this->records->save($record);

            $gradedRecords = $this->records->findGradedRecordsByStudentId($student->id());
            $newGpa = $this->gpaService->calculateCumulativeGpa($gradedRecords);
            $student->updateGpa($newGpa);
            $this->students->save($student);

            $graduationPath = $this->graduationPaths->findByStudentId($student->id());
            if ($graduationPath !== null && $course !== null && $grade->isPassing()) {
                $earnedCredits = array_sum(
                    array_column(
                        array_filter($gradedRecords, fn ($r) => $r['grade_points'] >= 2.0),
                        'credit_hours',
                    ),
                );
                $graduationPath->updateProgress(
                    \Modules\Academic\Domain\ValueObjects\Credits::of($earnedCredits),
                    $newGpa->value(),
                );
                $this->graduationPaths->save($graduationPath);
            }

            $events = array_merge(
                $record->releaseEvents(),
                $student->releaseEvents(),
            );
            $this->events->dispatch($events);

            $this->audit->log(
                actorUserId: $dto->recordedByUserId,
                action: 'grade.recorded',
                entityType: 'academic_record',
                entityId: $record->id()->value(),
                newValues: [
                    'enrollment_id' => $dto->enrollmentId,
                    'grade' => $dto->gradeLetter,
                    'gpa' => $newGpa->value(),
                ],
            );

            return [
                'record_id' => $record->id()->value(),
                'enrollment_id' => $enrollment->id()->value(),
                'grade' => $grade->letterValue(),
                'grade_points' => $grade->gradePoints(),
                'cumulative_gpa' => $newGpa->value(),
            ];
        });
    }
}
