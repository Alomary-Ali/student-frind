<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\TaskDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Exceptions\TaskNotFoundException;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CompleteTask
{
    public function __construct(
        private TaskRepositoryInterface $tasks,
        private EventDispatcherInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(string $taskId): TaskDto
    {
        $id = TaskId::fromString($taskId);
        $task = $this->tasks->findById($id)
            ?? throw TaskNotFoundException::forId($taskId);

        $task->complete();

        $this->tasks->save($task);
        $this->events->dispatch($task->releaseEvents());

        return $this->mapper->toTaskDto($task);
    }
}
