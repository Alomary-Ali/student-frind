<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use Modules\Productivity\Domain\Enums\ExamType;
use Modules\Productivity\Domain\Enums\ReadinessStatus;
use Modules\Productivity\Domain\Exceptions\InvalidExamIdException;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Shared\Domain\ValueObjects\UserId;

final class Exam
{
    private function __construct(
        private readonly ExamId $id,
        private readonly UserId $userId,
        private readonly string $courseId,
        private readonly string $title,
        private readonly ExamType $examType,
        private readonly \DateTimeImmutable $examDate,
        private readonly string $location,
        private string $status,
        private ?ReadinessStatus $readinessStatus,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        UserId $userId,
        string $courseId,
        string $title,
        ExamType $examType,
        \DateTimeImmutable $examDate,
        string $location,
        ?ReadinessStatus $readinessStatus = null,
    ): self {
        return new self(
            id: ExamId::generate(),
            userId: $userId,
            courseId: $courseId,
            title: $title,
            examType: $examType,
            examDate: $examDate,
            location: $location,
            status: 'scheduled',
            readinessStatus: $readinessStatus,
            createdAt: new \DateTimeImmutable,
            updatedAt: new \DateTimeImmutable,
        );
    }

    public static function fromArray(array $data): self
    {
        try {
            return new self(
                id: ExamId::fromString($data['id']),
                userId: UserId::fromString($data['user_id']),
                courseId: $data['course_id'],
                title: $data['title'],
                examType: ExamType::from($data['exam_type']),
                examDate: new \DateTimeImmutable($data['exam_date']),
                location: $data['location'],
                status: $data['status'],
                readinessStatus: isset($data['readiness_status']) ? ReadinessStatus::from($data['readiness_status']) : null,
                createdAt: new \DateTimeImmutable($data['created_at']),
                updatedAt: new \DateTimeImmutable($data['updated_at']),
            );
        } catch (\Exception $e) {
            throw new InvalidExamIdException($data['id'] ?? 'unknown');
        }
    }

    public function id(): ExamId
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

    public function examType(): ExamType
    {
        return $this->examType;
    }

    public function examDate(): \DateTimeImmutable
    {
        return $this->examDate;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function readinessStatus(): ?ReadinessStatus
    {
        return $this->readinessStatus;
    }

    public function updateReadinessStatus(ReadinessStatus $readinessStatus): void
    {
        $this->readinessStatus = $readinessStatus;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isUpcoming(): bool
    {
        return $this->examDate > new \DateTimeImmutable && $this->status === 'scheduled';
    }

    public function isPast(): bool
    {
        return $this->examDate < new \DateTimeImmutable;
    }

    public function isSoon(int $days = 7): bool
    {
        $soon = (new \DateTimeImmutable)->modify("+{$days} days");

        return $this->examDate <= $soon && $this->examDate > new \DateTimeImmutable;
    }

    public function markAsCompleted(): void
    {
        if ($this->status === 'completed') {
            throw new \DomainException('Exam already completed');
        }
        $this->status = 'completed';
    }

    public function markAsCancelled(): void
    {
        if ($this->status === 'completed') {
            throw new \DomainException('Cannot cancel completed exam');
        }
        $this->status = 'cancelled';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'user_id' => $this->userId->value(),
            'course_id' => $this->courseId,
            'title' => $this->title,
            'exam_type' => $this->examType->value,
            'exam_date' => $this->examDate->format('Y-m-d H:i:s'),
            'location' => $this->location,
            'status' => $this->status,
            'readiness_status' => $this->readinessStatus?->value,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
