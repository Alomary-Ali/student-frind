<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Events\CourseCompleted;
use Modules\Academic\Domain\ValueObjects\AcademicRecordId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Grade;

final class AcademicRecord
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly AcademicRecordId $id,
        private readonly EnrollmentId $enrollmentId,
        private readonly string $studentId,
        private readonly string $courseId,
        private readonly Grade $grade,
        private readonly DateTimeImmutable $recordedAt,
        private readonly string $recordedByUserId,
    ) {}

    public static function record(
        AcademicRecordId $id,
        EnrollmentId $enrollmentId,
        string $studentId,
        string $userId,
        string $courseId,
        Grade $grade,
        string $recordedByUserId,
    ): self {
        $record = new self(
            id: $id,
            enrollmentId: $enrollmentId,
            studentId: $studentId,
            courseId: $courseId,
            grade: $grade,
            recordedAt: new DateTimeImmutable(),
            recordedByUserId: $recordedByUserId,
        );

        $record->raise(new CourseCompleted(
            enrollmentId: $enrollmentId->value(),
            studentId: $studentId,
            userId: $userId,
            courseId: $courseId,
            grade: $grade->letterValue(),
            gradePoints: $grade->gradePoints(),
            completedAt: $record->recordedAt,
        ));

        return $record;
    }

    public static function reconstitute(
        AcademicRecordId $id,
        EnrollmentId $enrollmentId,
        string $studentId,
        string $courseId,
        Grade $grade,
        DateTimeImmutable $recordedAt,
        string $recordedByUserId,
    ): self {
        return new self($id, $enrollmentId, $studentId, $courseId, $grade, $recordedAt, $recordedByUserId);
    }

    public function id(): AcademicRecordId { return $this->id; }
    public function enrollmentId(): EnrollmentId { return $this->enrollmentId; }
    public function studentId(): string { return $this->studentId; }
    public function courseId(): string { return $this->courseId; }
    public function grade(): Grade { return $this->grade; }
    public function recordedAt(): DateTimeImmutable { return $this->recordedAt; }
    public function recordedByUserId(): string { return $this->recordedByUserId; }

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
