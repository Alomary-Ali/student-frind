<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use Modules\Productivity\Domain\Enums\ProjectStatus;
use Modules\Productivity\Domain\Exceptions\InvalidProjectIdException;
use Modules\Productivity\Domain\ValueObjects\ProjectId;
use Modules\Shared\Domain\ValueObjects\UserId;

final class Project
{
    private function __construct(
        private readonly ProjectId $id,
        private readonly UserId $userId,
        private readonly string $title,
        private readonly string $description,
        private readonly \DateTimeImmutable $startDate,
        private readonly \DateTimeImmutable $dueDate,
        private ProjectStatus $status,
        private int $progressPercentage,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        UserId $userId,
        string $title,
        string $description,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $dueDate,
    ): self {
        if ($startDate > $dueDate) {
            throw new \DomainException('Start date cannot be after due date');
        }

        return new self(
            id: ProjectId::generate(),
            userId: $userId,
            title: $title,
            description: $description,
            startDate: $startDate,
            dueDate: $dueDate,
            status: ProjectStatus::PLANNING,
            progressPercentage: 0,
            createdAt: new \DateTimeImmutable,
            updatedAt: new \DateTimeImmutable,
        );
    }

    public static function fromArray(array $data): self
    {
        try {
            return new self(
                id: ProjectId::fromString($data['id']),
                userId: UserId::fromString($data['user_id']),
                title: $data['title'],
                description: $data['description'],
                startDate: new \DateTimeImmutable($data['start_date']),
                dueDate: new \DateTimeImmutable($data['due_date']),
                status: ProjectStatus::from($data['status']),
                progressPercentage: (int) $data['progress_percentage'],
                createdAt: new \DateTimeImmutable($data['created_at']),
                updatedAt: new \DateTimeImmutable($data['updated_at']),
            );
        } catch (\Exception $e) {
            throw new InvalidProjectIdException($data['id'] ?? 'unknown');
        }
    }

    public function id(): ProjectId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function dueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function status(): ProjectStatus
    {
        return $this->status;
    }

    public function progressPercentage(): int
    {
        return $this->progressPercentage;
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
        return $this->dueDate < new \DateTimeImmutable && ! $this->status->isActive();
    }

    public function isDueSoon(int $days = 7): bool
    {
        $dueSoon = (new \DateTimeImmutable)->modify("+{$days} days");

        return $this->dueDate <= $dueSoon && ! $this->status->isActive();
    }

    public function start(): void
    {
        if ($this->status !== ProjectStatus::PLANNING) {
            throw new \DomainException('Project must be in planning status to start');
        }
        $this->status = ProjectStatus::IN_PROGRESS;
    }

    public function updateProgress(int $percentage): void
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \DomainException('Progress percentage must be between 0 and 100');
        }
        $this->progressPercentage = $percentage;

        if ($percentage === 100 && $this->status === ProjectStatus::IN_PROGRESS) {
            $this->status = ProjectStatus::COMPLETED;
        }
    }

    public function putOnHold(): void
    {
        if ($this->status === ProjectStatus::COMPLETED || $this->status === ProjectStatus::CANCELLED) {
            throw new \DomainException('Cannot put completed or cancelled project on hold');
        }
        $this->status = ProjectStatus::ON_HOLD;
    }

    public function resume(): void
    {
        if ($this->status !== ProjectStatus::ON_HOLD) {
            throw new \DomainException('Project must be on hold to resume');
        }
        $this->status = ProjectStatus::IN_PROGRESS;
    }

    public function complete(): void
    {
        if ($this->status === ProjectStatus::CANCELLED) {
            throw new \DomainException('Cannot complete cancelled project');
        }
        $this->status = ProjectStatus::COMPLETED;
        $this->progressPercentage = 100;
    }

    public function cancel(): void
    {
        if ($this->status === ProjectStatus::COMPLETED) {
            throw new \DomainException('Cannot cancel completed project');
        }
        $this->status = ProjectStatus::CANCELLED;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'user_id' => $this->userId->value(),
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->startDate->format('Y-m-d H:i:s'),
            'due_date' => $this->dueDate->format('Y-m-d H:i:s'),
            'status' => $this->status->value,
            'progress_percentage' => $this->progressPercentage,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
