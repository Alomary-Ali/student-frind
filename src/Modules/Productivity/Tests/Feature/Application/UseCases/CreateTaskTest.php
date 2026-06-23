<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Feature\Application\UseCases;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Application\DTOs\CreateGoalDto;
use Modules\Productivity\Application\DTOs\CreateTaskDto;
use Modules\Productivity\Application\UseCases\CreateGoal;
use Modules\Productivity\Application\UseCases\CreateTask;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Productivity\Infrastructure\Persistence\EloquentGoalRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentTaskRepository;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Tests\TestCase;

final class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    private CreateTask $useCase;
    private TaskRepositoryInterface $taskRepository;
    private GoalRepositoryInterface $goalRepository;
    private string $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->userId = $user->id;

        $this->taskRepository = new EloquentTaskRepository();
        $this->goalRepository = new EloquentGoalRepository();
        $this->useCase = new CreateTask(
            tasks: $this->taskRepository,
            goals: $this->goalRepository,
            events: $this->app->make(EventDispatcherInterface::class),
            mapper: $this->app->make(\Modules\Productivity\Application\Mappers\ProductivityMapper::class),
        );
    }

    public function test_task_can_be_created(): void
    {
        $dto = new CreateTaskDto(
            userId: $this->userId,
            title: 'Complete assignment',
            description: 'Finish the math homework',
            dueDate: '2026-06-25 23:59:59',
            priority: 'medium',
            linkedGoalId: null,
        );

        $result = $this->useCase->execute($dto);

        $this->assertSame('Complete assignment', $result->title);
        $this->assertSame($this->userId, $result->userId);
        $this->assertSame('pending', $result->status);
    }

    public function test_task_can_be_linked_to_goal(): void
    {
        $goalDto = new CreateGoalDto(
            userId: $this->userId,
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: '2026-12-31',
            priority: 'medium',
        );

        $createGoal = new CreateGoal(
            goals: $this->goalRepository,
            events: $this->app->make(EventDispatcherInterface::class),
            mapper: $this->app->make(\Modules\Productivity\Application\Mappers\ProductivityMapper::class),
        );

        $goal = $createGoal->execute($goalDto);

        $taskDto = new CreateTaskDto(
            userId: $this->userId,
            title: 'Test Task',
            description: 'Test Description',
            dueDate: '2026-06-25 23:59:59',
            priority: 'medium',
            linkedGoalId: $goal->id,
        );

        $result = $this->useCase->execute($taskDto);

        $this->assertSame($goal->id, $result->linkedGoalId);
    }

    public function test_task_is_persisted(): void
    {
        $dto = new CreateTaskDto(
            userId: $this->userId,
            title: 'Test Task',
            description: 'Test Description',
            dueDate: '2026-06-25 23:59:59',
            priority: 'medium',
            linkedGoalId: null,
        );

        $result = $this->useCase->execute($dto);

        $task = $this->taskRepository->findById(TaskId::fromString($result->id));

        $this->assertNotNull($task);
        $this->assertSame('Test Task', $task->title());
    }
}
