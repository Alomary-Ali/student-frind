<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Enums\GoalStatus;
use Modules\Productivity\Domain\Enums\GoalType;
use Modules\Productivity\Domain\Events\GoalCompleted;
use Modules\Productivity\Domain\Events\GoalCreated;
use Modules\Productivity\Domain\Exceptions\GoalAlreadyCompletedException;
use Modules\Productivity\Domain\Exceptions\InvalidGoalProgressException;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\GoalProgress;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;

final class Goal
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly GoalId $id,
        private readonly string $userId,
        private readonly string $title,
        private readonly string $description,
        private readonly DateTimeImmutable $targetDate,
        private PriorityLevel $priority,
        private GoalProgress $progress,
        private GoalStatus $status,
        private GoalType $goalType,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        GoalId $id,
        string $userId,
        string $title,
        string $description,
        DateTimeImmutable $targetDate,
        PriorityLevel $priority,
        GoalType $goalType = GoalType::Semester,
    ): self {
        $goal = new self(
            id: $id,
            userId: $userId,
            title: $title,
            description: $description,
            targetDate: $targetDate,
            priority: $priority,
            progress: GoalProgress::zero(),
            status: GoalStatus::Active,
            goalType: $goalType,
            createdAt: new DateTimeImmutable,
        );

        $goal->raise(new GoalCreated(
            goalId: $id->value(),
            userId: $userId,
            title: $title,
            targetDate: $targetDate->format('Y-m-d H:i:s'),
            priority: $priority->value(),
            occurredAt: new DateTimeImmutable,
        ));

        return $goal;
    }

    public static function reconstitute(
        GoalId $id,
        string $userId,
        string $title,
        string $description,
        DateTimeImmutable $targetDate,
        PriorityLevel $priority,
        GoalProgress $progress,
        GoalStatus $status,
        GoalType $goalType,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            title: $title,
            description: $description,
            targetDate: $targetDate,
            priority: $priority,
            progress: $progress,
            status: $status,
            goalType: $goalType,
            createdAt: $createdAt,
        );
    }

    public function updateProgress(GoalProgress $newProgress): void
    {
        if ($this->status->isCompleted()) {
            throw GoalAlreadyCompletedException::forGoal($this->id->value());
        }

        if ($newProgress->value() < 0 || $newProgress->value() > 100) {
            throw InvalidGoalProgressException::outOfRange($newProgress->value());
        }

        $this->progress = $newProgress;

        if ($newProgress->value() >= 100) {
            $this->complete();
        }
    }

    public function complete(): void
    {
        if ($this->status->isCompleted()) {
            return;
        }

        $this->status = GoalStatus::Completed;
        $this->progress = GoalProgress::complete();

        $this->raise(new GoalCompleted(
            goalId: $this->id->value(),
            userId: $this->userId,
            title: $this->title,
            completedAt: new DateTimeImmutable,
        ));
    }

    public function updatePriority(PriorityLevel $priority): void
    {
        $this->priority = $priority;
    }

    public function archive(): void
    {
        $this->status = GoalStatus::Archived;
    }

    public function id(): GoalId
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

    public function targetDate(): DateTimeImmutable
    {
        return $this->targetDate;
    }

    public function priority(): PriorityLevel
    {
        return $this->priority;
    }

    public function progress(): GoalProgress
    {
        return $this->progress;
    }

    public function status(): GoalStatus
    {
        return $this->status;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function goalType(): GoalType
    {
        return $this->goalType;
    }

    public function isOverdue(): bool
    {
        return $this->targetDate < new DateTimeImmutable && ! $this->status->isCompleted();
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
