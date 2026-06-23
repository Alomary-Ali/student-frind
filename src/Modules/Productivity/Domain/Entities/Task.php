<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Enums\TaskStatus;
use Modules\Productivity\Domain\Events\TaskCompleted;
use Modules\Productivity\Domain\Events\TaskCreated;
use Modules\Productivity\Domain\Exceptions\TaskAlreadyCompletedException;
use Modules\Productivity\Domain\Exceptions\TaskCannotBeModifiedException;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Domain\ValueObjects\TaskId;

final class Task
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly TaskId $id,
        private readonly string $userId,
        private readonly string $title,
        private readonly string $description,
        private readonly ?DateTimeImmutable $dueDate,
        private PriorityLevel $priority,
        private TaskStatus $status,
        private readonly ?GoalId $linkedGoalId,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $completedAt,
    ) {}

    public static function create(
        TaskId $id,
        string $userId,
        string $title,
        string $description,
        ?DateTimeImmutable $dueDate,
        PriorityLevel $priority,
        ?GoalId $linkedGoalId = null,
    ): self {
        $task = new self(
            id: $id,
            userId: $userId,
            title: $title,
            description: $description,
            dueDate: $dueDate,
            priority: $priority,
            status: TaskStatus::Pending,
            linkedGoalId: $linkedGoalId,
            createdAt: new DateTimeImmutable,
            completedAt: null,
        );

        $task->raise(new TaskCreated(
            taskId: $id->value(),
            userId: $userId,
            title: $title,
            dueDate: $dueDate?->format('Y-m-d H:i:s'),
            priority: $priority->value(),
            linkedGoalId: $linkedGoalId?->value(),
            occurredAt: new DateTimeImmutable,
        ));

        return $task;
    }

    public static function reconstitute(
        TaskId $id,
        string $userId,
        string $title,
        string $description,
        ?DateTimeImmutable $dueDate,
        PriorityLevel $priority,
        TaskStatus $status,
        ?GoalId $linkedGoalId,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $completedAt,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            title: $title,
            description: $description,
            dueDate: $dueDate,
            priority: $priority,
            status: $status,
            linkedGoalId: $linkedGoalId,
            createdAt: $createdAt,
            completedAt: $completedAt,
        );
    }

    public function start(): void
    {
        if ($this->status->isCompleted()) {
            throw TaskAlreadyCompletedException::forTask($this->id->value());
        }

        $this->status = TaskStatus::InProgress;
    }

    public function complete(): void
    {
        if ($this->status->isCompleted()) {
            return;
        }

        $this->status = TaskStatus::Completed;

        $this->raise(new TaskCompleted(
            taskId: $this->id->value(),
            userId: $this->userId,
            title: $this->title,
            linkedGoalId: $this->linkedGoalId?->value(),
            completedAt: new DateTimeImmutable,
        ));
    }

    public function postpone(): void
    {
        if ($this->status->isCompleted()) {
            throw TaskCannotBeModifiedException::taskCompleted($this->id->value());
        }

        $this->status = TaskStatus::Postponed;
    }

    public function cancel(): void
    {
        if ($this->status->isCompleted()) {
            throw TaskCannotBeModifiedException::taskCompleted($this->id->value());
        }

        $this->status = TaskStatus::Cancelled;
    }

    public function updateTitle(string $title): void
    {
        if ($this->status->isCompleted()) {
            throw TaskCannotBeModifiedException::taskCompleted($this->id->value());
        }

        $this->title = $title;
    }

    public function updateDescription(string $description): void
    {
        if ($this->status->isCompleted()) {
            throw TaskCannotBeModifiedException::taskCompleted($this->id->value());
        }

        $this->description = $description;
    }

    public function updateDueDate(?DateTimeImmutable $dueDate): void
    {
        if ($this->status->isCompleted()) {
            throw TaskCannotBeModifiedException::taskCompleted($this->id->value());
        }

        $this->dueDate = $dueDate;
    }

    public function updatePriority(PriorityLevel $priority): void
    {
        $this->priority = $priority;
    }

    public function id(): TaskId
    {
        return $this->id;
    }

    public function userId(): string
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

    public function dueDate(): ?DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function priority(): PriorityLevel
    {
        return $this->priority;
    }

    public function status(): TaskStatus
    {
        return $this->status;
    }

    public function linkedGoalId(): ?GoalId
    {
        return $this->linkedGoalId;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function completedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function isOverdue(): bool
    {
        return $this->dueDate !== null
            && $this->dueDate < new DateTimeImmutable
            && ! $this->status->isCompleted();
    }

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
