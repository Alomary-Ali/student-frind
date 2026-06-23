<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\AcademicStatus;
use Modules\Academic\Domain\Events\GpaUpdated;
use Modules\Academic\Domain\Events\StudentCreated;
use Modules\Academic\Domain\Exceptions\DuplicateEnrollmentException;
use Modules\Academic\Domain\Exceptions\StudentNotEligibleException;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;

final class Student
{
    /** @var list<object> */
    private array $domainEvents = [];

    /** @var list<Enrollment> */
    private array $enrollments = [];

    private function __construct(
        private readonly StudentId $id,
        private readonly string $userId,
        private readonly string $studentNumber,
        private AcademicStatus $academicStatus,
        private AcademicStanding $academicStanding,
        private Gpa $cumulativeGpa,
        private ?Gpa $semesterGpa,
        private ?string $currentSemesterId,
        private readonly ?string $institutionId,
        private readonly ?string $universityId,
        private readonly ?string $collegeId,
        private readonly ?string $departmentId,
        private readonly ?string $majorId,
        private readonly string $level,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        StudentId $id,
        string $userId,
        string $studentNumber,
        ?string $institutionId = null,
        ?string $universityId = null,
        ?string $collegeId = null,
        ?string $departmentId = null,
        ?string $majorId = null,
        string $level = '1',
    ): self {
        $student = new self(
            id: $id,
            userId: $userId,
            studentNumber: $studentNumber,
            academicStatus: AcademicStatus::Active,
            academicStanding: AcademicStanding::GoodStanding,
            cumulativeGpa: Gpa::zero(),
            semesterGpa: null,
            currentSemesterId: null,
            institutionId: $institutionId,
            universityId: $universityId,
            collegeId: $collegeId,
            departmentId: $departmentId,
            majorId: $majorId,
            level: $level,
            createdAt: new DateTimeImmutable(),
        );

        $student->raise(new StudentCreated(
            studentId: $id->value(),
            userId: $userId,
            studentNumber: $studentNumber,
            occurredAt: new DateTimeImmutable(),
        ));

        return $student;
    }

    public static function reconstitute(
        StudentId $id,
        string $userId,
        string $studentNumber,
        AcademicStatus $academicStatus,
        AcademicStanding $academicStanding,
        Gpa $cumulativeGpa,
        ?Gpa $semesterGpa,
        ?string $currentSemesterId,
        ?string $institutionId,
        ?string $universityId,
        ?string $collegeId,
        ?string $departmentId,
        ?string $majorId,
        string $level,
        DateTimeImmutable $createdAt,
        array $enrollments = [],
    ): self {
        $student = new self(
            id: $id,
            userId: $userId,
            studentNumber: $studentNumber,
            academicStatus: $academicStatus,
            academicStanding: $academicStanding,
            cumulativeGpa: $cumulativeGpa,
            semesterGpa: $semesterGpa,
            currentSemesterId: $currentSemesterId,
            institutionId: $institutionId,
            universityId: $universityId,
            collegeId: $collegeId,
            departmentId: $departmentId,
            majorId: $majorId,
            level: $level,
            createdAt: $createdAt,
        );
        $student->enrollments = $enrollments;

        return $student;
    }

    public function enrollInCourse(
        EnrollmentId $enrollmentId,
        CourseId $courseId,
        SemesterId $semesterId,
    ): Enrollment {
        if (! $this->academicStatus->canEnroll()) {
            throw StudentNotEligibleException::cannotEnroll(
                $this->id->value(),
                'Student status is ' . $this->academicStatus->value,
            );
        }

        foreach ($this->enrollments as $existing) {
            if ($existing->isActiveFor($courseId, $semesterId)) {
                throw DuplicateEnrollmentException::forStudentAndCourse(
                    $this->id->value(),
                    $courseId->value(),
                    $semesterId->value(),
                );
            }
        }

        $enrollment = Enrollment::create(
            id: $enrollmentId,
            studentId: $this->id,
            userId: $this->userId,
            courseId: $courseId,
            semesterId: $semesterId,
        );

        $this->enrollments[] = $enrollment;

        return $enrollment;
    }

    public function updateGpa(Gpa $newGpa): void
    {
        $previous = $this->cumulativeGpa;
        $this->cumulativeGpa = $newGpa;

        $this->raise(new GpaUpdated(
            studentId: $this->id->value(),
            previousGpa: $previous->value(),
            newGpa: $newGpa->value(),
            updatedAt: new DateTimeImmutable(),
        ));

        $this->updateStandingFromGpa($newGpa);
    }

    public function updateStanding(AcademicStanding $standing): void
    {
        $this->academicStanding = $standing;
    }

    public function graduate(): void
    {
        $this->academicStatus = AcademicStatus::Graduated;
    }

    private function updateStandingFromGpa(Gpa $gpa): void
    {
        $value = $gpa->value();

        $this->academicStanding = match (true) {
            $value >= 2.0 => AcademicStanding::GoodStanding,
            $value >= 1.5 => AcademicStanding::Probation,
            default       => AcademicStanding::Suspension,
        };
    }

    public function id(): StudentId { return $this->id; }
    public function userId(): string { return $this->userId; }
    public function studentNumber(): string { return $this->studentNumber; }
    public function academicStatus(): AcademicStatus { return $this->academicStatus; }
    public function academicStanding(): AcademicStanding { return $this->academicStanding; }
    public function cumulativeGpa(): Gpa { return $this->cumulativeGpa; }
    public function semesterGpa(): ?Gpa { return $this->semesterGpa; }
    public function currentSemesterId(): ?string { return $this->currentSemesterId; }
    public function institutionId(): ?string { return $this->institutionId; }
    public function universityId(): ?string { return $this->universityId; }
    public function collegeId(): ?string { return $this->collegeId; }
    public function departmentId(): ?string { return $this->departmentId; }
    public function majorId(): ?string { return $this->majorId; }
    public function level(): string { return $this->level; }
    public function createdAt(): DateTimeImmutable { return $this->createdAt; }

    /** @return list<Enrollment> */
    public function enrollments(): array { return $this->enrollments; }

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
