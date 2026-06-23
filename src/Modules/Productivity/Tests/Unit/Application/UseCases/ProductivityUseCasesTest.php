<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Application\UseCases;

use Modules\Productivity\Application\DTOs\CalendarEventDto;
use Modules\Productivity\Application\DTOs\CreateCalendarEventDto;
use Modules\Productivity\Application\DTOs\CreateReminderDto;
use Modules\Productivity\Application\DTOs\GoalDto;
use Modules\Productivity\Application\DTOs\ProductivityDashboardDto;
use Modules\Productivity\Application\DTOs\ProductivitySnapshotDto;
use Modules\Productivity\Application\DTOs\ReminderDto;
use Modules\Productivity\Application\DTOs\TaskDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CompleteTask;
use Modules\Productivity\Application\UseCases\CreateCalendarEvent;
use Modules\Productivity\Application\UseCases\CreateReminder;
use Modules\Productivity\Application\UseCases\GenerateProductivitySnapshot;
use Modules\Productivity\Application\UseCases\GetProductivityDashboard;
use Modules\Productivity\Application\UseCases\UpdateGoalProgress;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ProductivitySnapshotRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\Exceptions\GoalNotFoundException;
use Modules\Productivity\Domain\Exceptions\TaskNotFoundException;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class ProductivityUseCasesTest extends TestCase
{
    private ProductivityMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new ProductivityMapper;
    }

    private function createTestGoal(): Goal
    {
        return Goal::create(
            id: GoalId::generate(),
            userId: 'user-1',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new \DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );
    }

    private function createTestTask(): Task
    {
        return Task::create(
            id: TaskId::generate(),
            userId: 'user-1',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new \DateTimeImmutable('2026-07-01'),
            priority: PriorityLevel::medium(),
        );
    }

    public function test_update_goal_progress_execute(): void
    {
        $goal = $this->createTestGoal();
        $goal->releaseEvents();

        $repo = $this->createMock(GoalRepositoryInterface::class);
        $repo->expects($this->once())->method('findById')->willReturn($goal);
        $repo->expects($this->once())->method('save');

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new UpdateGoalProgress($repo, $events, $this->mapper);
        $result = $useCase->execute($goal->id()->value(), 75.0);

        $this->assertInstanceOf(GoalDto::class, $result);
        $this->assertSame(75.0, $result->progress);
    }

    public function test_update_goal_progress_throws_when_not_found(): void
    {
        $id = '00000000-0000-4000-a000-000000000000';
        $repo = $this->createMock(GoalRepositoryInterface::class);
        $repo->expects($this->once())->method('findById')->willReturn(null);

        $useCase = new UpdateGoalProgress($repo, $this->createMock(EventDispatcherInterface::class), $this->mapper);

        $this->expectException(GoalNotFoundException::class);
        $useCase->execute($id, 50.0);
    }

    public function test_complete_task_execute(): void
    {
        $task = $this->createTestTask();
        $task->releaseEvents();

        $repo = $this->createMock(TaskRepositoryInterface::class);
        $repo->expects($this->once())->method('findById')->willReturn($task);
        $repo->expects($this->once())->method('save');

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new CompleteTask($repo, $events, $this->mapper);
        $result = $useCase->execute($task->id()->value());

        $this->assertInstanceOf(TaskDto::class, $result);
        $this->assertSame('completed', $result->status);
    }

    public function test_complete_task_throws_when_not_found(): void
    {
        $id = '00000000-0000-4000-a000-000000000000';
        $repo = $this->createMock(TaskRepositoryInterface::class);
        $repo->expects($this->once())->method('findById')->willReturn(null);

        $useCase = new CompleteTask($repo, $this->createMock(EventDispatcherInterface::class), $this->mapper);

        $this->expectException(TaskNotFoundException::class);
        $useCase->execute($id);
    }

    public function test_get_productivity_dashboard_execute(): void
    {
        $goalsRepo = $this->createMock(GoalRepositoryInterface::class);
        $goalsRepo->expects($this->once())->method('findByUserId')->willReturn([$this->createTestGoal()]);

        $tasksRepo = $this->createMock(TaskRepositoryInterface::class);
        $tasksRepo->expects($this->once())->method('findByUserId')->willReturn([$this->createTestTask()]);

        $remindersRepo = $this->createMock(ReminderRepositoryInterface::class);
        $remindersRepo->expects($this->once())->method('findByUserId')->willReturn([]);

        $eventsRepo = $this->createMock(CalendarEventRepositoryInterface::class);
        $eventsRepo->expects($this->once())->method('findByUserId')->willReturn([]);

        $useCase = new GetProductivityDashboard($goalsRepo, $tasksRepo, $remindersRepo, $eventsRepo, $this->mapper);
        $result = $useCase->execute('user-1');

        $this->assertInstanceOf(ProductivityDashboardDto::class, $result);
        $this->assertSame('user-1', $result->userId);
        $this->assertSame(1, $result->activeGoals);
        $this->assertSame(0, $result->completedGoals);
    }

    public function test_generate_productivity_snapshot_execute(): void
    {
        $goalsRepo = $this->createMock(GoalRepositoryInterface::class);
        $goalsRepo->expects($this->once())->method('findByUserId')->willReturn([$this->createTestGoal()]);

        $tasksRepo = $this->createMock(TaskRepositoryInterface::class);
        $tasksRepo->expects($this->once())->method('findByUserId')->willReturn([$this->createTestTask()]);

        $snapshotsRepo = $this->createMock(ProductivitySnapshotRepositoryInterface::class);
        $snapshotsRepo->expects($this->once())->method('save');

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new GenerateProductivitySnapshot($goalsRepo, $tasksRepo, $snapshotsRepo, $events, $this->mapper);
        $result = $useCase->execute('user-1', '2026-06-23');

        $this->assertInstanceOf(ProductivitySnapshotDto::class, $result);
        $this->assertSame('user-1', $result->userId);
        $this->assertSame(1, $result->totalGoals);
        $this->assertSame(1, $result->totalTasks);
    }

    public function test_create_calendar_event_execute(): void
    {
        $dto = new CreateCalendarEventDto(
            userId: 'user-1',
            title: 'Test Event',
            description: 'Test Description',
            startsAt: '2026-07-01 10:00:00',
            endsAt: '2026-07-01 12:00:00',
            isAllDay: false,
            linkedTaskId: null,
        );

        $eventsRepo = $this->createMock(CalendarEventRepositoryInterface::class);
        $eventsRepo->expects($this->once())->method('save');

        $tasksRepo = $this->createMock(TaskRepositoryInterface::class);

        $useCase = new CreateCalendarEvent($eventsRepo, $tasksRepo, $this->mapper);
        $result = $useCase->execute($dto);

        $this->assertInstanceOf(CalendarEventDto::class, $result);
        $this->assertSame('Test Event', $result->title);
        $this->assertSame('user-1', $result->userId);
    }

    public function test_create_reminder_execute(): void
    {
        $dto = new CreateReminderDto(
            userId: 'user-1',
            message: 'Test Reminder',
            triggerAt: '2026-07-01 09:00:00',
            type: 'in_app',
            linkedTaskId: null,
        );

        $remindersRepo = $this->createMock(ReminderRepositoryInterface::class);
        $remindersRepo->expects($this->once())->method('save');

        $tasksRepo = $this->createMock(TaskRepositoryInterface::class);

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new CreateReminder($remindersRepo, $tasksRepo, $events, $this->mapper);
        $result = $useCase->execute($dto);

        $this->assertInstanceOf(ReminderDto::class, $result);
        $this->assertSame('Test Reminder', $result->message);
        $this->assertSame('user-1', $result->userId);
    }

    public function test_create_reminder_with_linked_task_throws_when_task_not_found(): void
    {
        $dto = new CreateReminderDto(
            userId: 'user-1',
            message: 'Test',
            triggerAt: '2026-07-01 09:00:00',
            type: 'in_app',
            linkedTaskId: '00000000-0000-4000-a000-000000000000',
        );

        $remindersRepo = $this->createMock(ReminderRepositoryInterface::class);

        $tasksRepo = $this->createMock(TaskRepositoryInterface::class);
        $tasksRepo->expects($this->once())->method('findById')->willReturn(null);

        $useCase = new CreateReminder($remindersRepo, $tasksRepo, $this->createMock(EventDispatcherInterface::class), $this->mapper);

        $this->expectException(TaskNotFoundException::class);
        $useCase->execute($dto);
    }
}
