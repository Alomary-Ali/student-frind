<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\Events\StudentEnrolled;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;

final class Enrollment
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly EnrollmentId $id,
        private readonly StudentId $studentId,
        private readonly CourseId $courseId,
        private readonly SemesterId $semesterId,
        private EnrollmentStatus $status,
        private readonly DateTimeImmutable $enrolledAt,
    ) {}

    public static function create(
        EnrollmentId $id,
        StudentId $studentId,
        string $userId,
        CourseId $courseId,
        SemesterId $semesterId,
    ): self {
        $enrollment = new self(
            id: $id,
            studentId: $studentId,
            courseId: $courseId,
            semesterId: $semesterId,
            status: EnrollmentStatus::Enrolled,
            enrolledAt: new DateTimeImmutable(),
        );

        $enrollment->raise(new StudentEnrolled(
            enrollmentId: $id->value(),
            studentId: $studentId->value(),
            userId: $userId,
            courseId: $courseId->value(),
            semesterId: $semesterId->value(),
            enrolledAt: $enrollment->enrolledAt,
        ));

        return $enrollment;
    }

    public static function reconstitute(
        EnrollmentId $id,
        StudentId $studentId,
        CourseId $courseId,
        SemesterId $semesterId,
        EnrollmentStatus $status,
        DateTimeImmutable $enrolledAt,
    ): self {
        return new self($id, $studentId, $courseId, $semesterId, $status, $enrolledAt);
    }

    public function complete(): void
    {
        $this->status = EnrollmentStatus::Completed;
    }

    public function drop(): void
    {
        $this->status = EnrollmentStatus::Dropped;
    }

    public function isActiveFor(CourseId $courseId, SemesterId $semesterId): bool
    {
        return $this->courseId->equals($courseId)
            && $this->semesterId->equals($semesterId)
            && $this->status !== EnrollmentStatus::Dropped;
    }

    public function isCompleted(): bool
    {
        return $this->status === EnrollmentStatus::Completed;
    }

    public function id(): EnrollmentId { return $this->id; }
    public function studentId(): StudentId { return $this->studentId; }
    public function courseId(): CourseId { return $this->courseId; }
    public function semesterId(): SemesterId { return $this->semesterId; }
    public function status(): EnrollmentStatus { return $this->status; }
    public function enrolledAt(): DateTimeImmutable { return $this->enrolledAt; }

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
