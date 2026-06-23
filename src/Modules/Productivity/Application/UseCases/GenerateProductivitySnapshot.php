<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\ProductivitySnapshotDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ProductivitySnapshotRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Entities\ProductivitySnapshot;
use Modules\Productivity\Domain\ValueObjects\ProductivitySnapshotId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class GenerateProductivitySnapshot
{
    public function __construct(
        private GoalRepositoryInterface $goals,
        private TaskRepositoryInterface $tasks,
        private ProductivitySnapshotRepositoryInterface $snapshots,
        private EventDispatcherInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(string $userId, string $snapshotDate): ProductivitySnapshotDto
    {
        $allGoals = $this->goals->findByUserId($userId);
        $allTasks = $this->tasks->findByUserId($userId);

        $totalGoals = count($allGoals);
        $completedGoals = count(array_filter($allGoals, fn ($g) => $g->status()->isCompleted()));

        $totalTasks = count($allTasks);
        $completedTasks = count(array_filter($allTasks, fn ($t) => $t->status()->isCompleted()));
        $overdueTasks = count(array_filter($allTasks, fn ($t) => $t->isOverdue()));

        $snapshot = ProductivitySnapshot::create(
            id: ProductivitySnapshotId::generate(),
            userId: $userId,
            totalGoals: $totalGoals,
            completedGoals: $completedGoals,
            totalTasks: $totalTasks,
            completedTasks: $completedTasks,
            overdueTasks: $overdueTasks,
            snapshotDate: new \DateTimeImmutable($snapshotDate),
        );

        $this->snapshots->save($snapshot);
        $this->events->dispatch($snapshot->releaseEvents());

        return new ProductivitySnapshotDto(
            id: $snapshot->id()->value(),
            userId: $snapshot->userId(),
            totalGoals: $snapshot->totalGoals(),
            completedGoals: $snapshot->completedGoals(),
            totalTasks: $snapshot->totalTasks(),
            completedTasks: $snapshot->completedTasks(),
            overdueTasks: $snapshot->overdueTasks(),
            completionRate: $snapshot->completionRate(),
            snapshotDate: $snapshot->snapshotDate()->format('Y-m-d'),
            createdAt: $snapshot->createdAt()->format('Y-m-d H:i:s'),
        );
    }
}
