<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Application\DTOs\EnrollStudentDto;
use Modules\Academic\Application\DTOs\EnrollmentDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Exceptions\CourseNotFoundException;
use Modules\Academic\Domain\Exceptions\DuplicateEnrollmentException;
use Modules\Academic\Domain\Exceptions\PrerequisiteNotMetException;
use Modules\Academic\Domain\Exceptions\SemesterNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\Services\PrerequisiteValidationService;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class EnrollStudentInCourse
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private CourseRepositoryInterface $courses,
        private SemesterRepositoryInterface $semesters,
        private EnrollmentRepositoryInterface $enrollments,
        private TransactionManagerInterface $transactions,
        private EventDispatcherInterface $events,
        private AcademicAuditLoggerInterface $audit,
        private AcademicMapper $mapper,
        private PrerequisiteValidationService $prerequisiteValidator,
    ) {}

    public function execute(EnrollStudentDto $dto): EnrollmentDto
    {
        return $this->transactions->runInTransaction(function () use ($dto) {
            $studentId = StudentId::fromString($dto->studentId);
            $courseId = CourseId::fromString($dto->courseId);
            $semesterId = SemesterId::fromString($dto->semesterId);

            $student = $this->students->findById($studentId)
                ?? throw StudentNotFoundException::forId($dto->studentId);

            $course = $this->courses->findById($courseId)
                ?? throw CourseNotFoundException::forId($dto->courseId);

            $this->semesters->findById($semesterId)
                ?? throw SemesterNotFoundException::forId($dto->semesterId);

            $course->ensureActive();

            // Validate prerequisites
            $prerequisites = $this->courses->findPrerequisites($courseId);
            $completedEnrollments = $this->enrollments->findCompletedByStudent($studentId);
            $this->prerequisiteValidator->validatePrerequisites($prerequisites, $completedEnrollments);

            if ($this->enrollments->existsForStudentCourseSemester($studentId, $courseId, $semesterId)) {
                throw DuplicateEnrollmentException::forStudentAndCourse(
                    $dto->studentId, $dto->courseId, $dto->semesterId
                );
            }

            $enrollment = $student->enrollInCourse(
                enrollmentId: EnrollmentId::generate(),
                courseId: $courseId,
                semesterId: $semesterId,
            );

            $this->students->save($student);
            $this->enrollments->save($enrollment);

            $events = array_merge($student->releaseEvents(), $enrollment->releaseEvents());
            $this->events->dispatch($events);

            $this->audit->log(
                actorUserId: $dto->actorUserId,
                action: 'enrollment.created',
                entityType: 'academic_enrollment',
                entityId: $enrollment->id()->value(),
                newValues: [
                    'student_id' => $dto->studentId,
                    'course_id' => $dto->courseId,
                    'semester_id' => $dto->semesterId,
                ],
            );

            return $this->mapper->toEnrollmentDto($enrollment);
        });
    }
}
