<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use Modules\Productivity\Domain\Enums\AssignmentStatus;
use Modules\Productivity\Domain\Exceptions\InvalidAssignmentIdException;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Shared\Domain\ValueObjects\UserId;

final class Assignment
{
    private function __construct(
        private readonly AssignmentId $id,
        private readonly UserId $userId,
        private readonly string $courseId,
        private readonly string $title,
        private readonly string $description,
        private readonly \DateTimeImmutable $assignedAt,
        private readonly \DateTimeImmutable $dueDate,
        private AssignmentStatus $status,
        private ?string $grade,
        private ?string $submissionUrl,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        UserId $userId,
        string $courseId,
        string $title,
        string $description,
        \DateTimeImmutable $dueDate,
    ): self {
        return new self(
            id: AssignmentId::generate(),
            userId: $userId,
            courseId: $courseId,
            title: $title,
            description: $description,
            assignedAt: new \DateTimeImmutable,
            dueDate: $dueDate,
            status: AssignmentStatus::ASSIGNED,
            grade: null,
            submissionUrl: null,
            createdAt: new \DateTimeImmutable,
            updatedAt: new \DateTimeImmutable,
        );
    }

    public static function fromArray(array $data): self
    {
        try {
            return new self(
                id: AssignmentId::fromString($data['id']),
                userId: UserId::fromString($data['user_id']),
                courseId: $data['course_id'],
                title: $data['title'],
                description: $data['description'],
                assignedAt: new \DateTimeImmutable($data['assigned_at']),
                dueDate: new \DateTimeImmutable($data['due_date']),
                status: AssignmentStatus::from($data['status']),
                grade: $data['grade'] ?? null,
                submissionUrl: $data['submission_url'] ?? null,
                createdAt: new \DateTimeImmutable($data['created_at']),
                updatedAt: new \DateTimeImmutable($data['updated_at']),
            );
        } catch (\Exception $e) {
            throw new InvalidAssignmentIdException($data['id'] ?? 'unknown');
        }
    }

    public function id(): AssignmentId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function courseId(): string
    {
        return $this->courseId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function assignedAt(): \DateTimeImmutable
    {
        return $this->assignedAt;
    }

    public function dueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function status(): AssignmentStatus
    {
        return $this->status;
    }

    public function grade(): ?string
    {
        return $this->grade;
    }

    public function submissionUrl(): ?string
    {
        return $this->submissionUrl;
    }

    public function assignGrade(string $grade): void
    {
        if ($this->status !== AssignmentStatus::SUBMITTED && $this->status !== AssignmentStatus::LATE) {
            throw new \DomainException('Can only grade submitted assignments');
        }
        $this->status = AssignmentStatus::GRADED;
        $this->grade = $grade;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isOverdue(): bool
    {
        return $this->dueDate < new \DateTimeImmutable && ! $this->status->isCompleted();
    }

    public function isDueSoon(int $days = 3): bool
    {
        $dueSoon = (new \DateTimeImmutable)->modify("+{$days} days");

        return $this->dueDate <= $dueSoon && ! $this->status->isCompleted();
    }

    public function markAsInProgress(): void
    {
        if ($this->status->isCompleted()) {
            throw new \DomainException('Cannot mark completed assignment as in progress');
        }
        $this->status = AssignmentStatus::IN_PROGRESS;
    }

    public function markAsSubmitted(string $submissionUrl): void
    {
        if ($this->status === AssignmentStatus::SUBMITTED || $this->status === AssignmentStatus::GRADED) {
            throw new \DomainException('Assignment already submitted');
        }
        $this->status = AssignmentStatus::SUBMITTED;
        $this->submissionUrl = $submissionUrl;
    }

    public function markAsLate(): void
    {
        if ($this->status === AssignmentStatus::GRADED) {
            throw new \DomainException('Cannot mark graded assignment as late');
        }
        $this->status = AssignmentStatus::LATE;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'user_id' => $this->userId->value(),
            'course_id' => $this->courseId,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_at' => $this->assignedAt->format('Y-m-d H:i:s'),
            'due_date' => $this->dueDate->format('Y-m-d H:i:s'),
            'status' => $this->status->value,
            'grade' => $this->grade,
            'submission_url' => $this->submissionUrl,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
