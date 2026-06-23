<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\CreateTaskDto;
use Modules\Productivity\Application\DTOs\TaskDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\Exceptions\GoalNotFoundException;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateTask
{
    public function __construct(
        private TaskRepositoryInterface $tasks,
        private GoalRepositoryInterface $goals,
        private EventDispatcherInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(CreateTaskDto $dto): TaskDto
    {
        $linkedGoalId = null;

        if ($dto->linkedGoalId !== null) {
            $goalId = GoalId::fromString($dto->linkedGoalId);
            $goal = $this->goals->findById($goalId)
                ?? throw GoalNotFoundException::forId($dto->linkedGoalId);
            $linkedGoalId = $goal->id();
        }

        $task = Task::create(
            id: TaskId::generate(),
            userId: $dto->userId,
            title: $dto->title,
            description: $dto->description,
            dueDate: $dto->dueDate ? new \DateTimeImmutable($dto->dueDate) : null,
            priority: PriorityLevel::fromString($dto->priority),
            linkedGoalId: $linkedGoalId,
        );

        $this->tasks->save($task);
        $this->events->dispatch($task->releaseEvents());

        return $this->mapper->toTaskDto($task);
    }
}
