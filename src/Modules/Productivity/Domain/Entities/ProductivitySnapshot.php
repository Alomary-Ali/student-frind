<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Events\ProductivitySnapshotGenerated;
use Modules\Productivity\Domain\ValueObjects\ProductivitySnapshotId;

final class ProductivitySnapshot
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly ProductivitySnapshotId $id,
        private readonly string $userId,
        private readonly int $totalGoals,
        private readonly int $completedGoals,
        private readonly int $totalTasks,
        private readonly int $completedTasks,
        private readonly int $overdueTasks,
        private readonly float $completionRate,
        private readonly DateTimeImmutable $snapshotDate,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        ProductivitySnapshotId $id,
        string $userId,
        int $totalGoals,
        int $completedGoals,
        int $totalTasks,
        int $completedTasks,
        int $overdueTasks,
        DateTimeImmutable $snapshotDate,
    ): self {
        $completionRate = $totalTasks > 0
            ? ($completedTasks / $totalTasks) * 100
            : 0.0;

        $snapshot = new self(
            id: $id,
            userId: $userId,
            totalGoals: $totalGoals,
            completedGoals: $completedGoals,
            totalTasks: $totalTasks,
            completedTasks: $completedTasks,
            overdueTasks: $overdueTasks,
            completionRate: $completionRate,
            snapshotDate: $snapshotDate,
            createdAt: new DateTimeImmutable(),
        );

        $snapshot->raise(new ProductivitySnapshotGenerated(
            snapshotId: $id->value(),
            userId: $userId,
            totalGoals: $totalGoals,
            completedGoals: $completedGoals,
            totalTasks: $totalTasks,
            completedTasks: $completedTasks,
            overdueTasks: $overdueTasks,
            completionRate: $completionRate,
            snapshotDate: $snapshotDate->format('Y-m-d'),
            occurredAt: new DateTimeImmutable(),
        ));

        return $snapshot;
    }

    public static function reconstitute(
        ProductivitySnapshotId $id,
        string $userId,
        int $totalGoals,
        int $completedGoals,
        int $totalTasks,
        int $completedTasks,
        int $overdueTasks,
        float $completionRate,
        DateTimeImmutable $snapshotDate,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            totalGoals: $totalGoals,
            completedGoals: $completedGoals,
            totalTasks: $totalTasks,
            completedTasks: $completedTasks,
            overdueTasks: $overdueTasks,
            completionRate: $completionRate,
            snapshotDate: $snapshotDate,
            createdAt: $createdAt,
        );
    }

    public function id(): ProductivitySnapshotId
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function totalGoals(): int
    {
        return $this->totalGoals;
    }

    public function completedGoals(): int
    {
        return $this->completedGoals;
    }

    public function totalTasks(): int
    {
        return $this->totalTasks;
    }

    public function completedTasks(): int
    {
        return $this->completedTasks;
    }

    public function overdueTasks(): int
    {
        return $this->overdueTasks;
    }

    public function completionRate(): float
    {
        return $this->completionRate;
    }

    public function snapshotDate(): DateTimeImmutable
    {
        return $this->snapshotDate;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
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
