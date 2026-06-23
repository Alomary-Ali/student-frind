<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Productivity\Application\DTOs\CreateTaskDto;
use Modules\Productivity\Application\DTOs\TaskDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CompleteTask;
use Modules\Productivity\Application\UseCases\CreateTask;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Exceptions\TaskNotFoundException;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;

final readonly class TaskController
{
    public function __construct(
        private CreateTask $createTask,
        private CompleteTask $completeTask,
        private TaskRepositoryInterface $tasks,
        private ProductivityMapper $mapper,
    ) {}

    public function store(): JsonResponse
    {
        try {
            $dto = new CreateTaskDto(
                userId: request()->input('user_id'),
                title: request()->input('title'),
                description: request()->input('description'),
                dueDate: request()->input('due_date'),
                priority: request()->input('priority'),
                linkedGoalId: request()->input('linked_goal_id'),
            );

            $task = $this->createTask->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task,
            ], 201);
        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'code' => 'USER_NOT_FOUND',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 'INTERNAL_ERROR',
                'message' => 'Failed to create task',
                'errors' => [],
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $taskId = \Modules\Productivity\Domain\ValueObjects\TaskId::fromString($id);
            $task = $this->tasks->findById($taskId);

            if ($task === null) {
                throw TaskNotFoundException::forId($id);
            }

            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $this->mapper->toTaskDto($task),
            ]);
        } catch (TaskNotFoundException $e) {
            return response()->json([
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 404);
        }
    }

    public function index(string $userId): JsonResponse
    {
        $tasks = $this->tasks->findByUserId($userId);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $this->mapper->toTaskDtoList($tasks),
        ]);
    }

    public function complete(string $id): JsonResponse
    {
        try {
            $task = $this->completeTask->execute($id);

            return response()->json([
                'success' => true,
                'message' => 'Task completed successfully',
                'data' => $task,
            ]);
        } catch (TaskNotFoundException $e) {
            return response()->json([
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 404);
        }
    }
}
