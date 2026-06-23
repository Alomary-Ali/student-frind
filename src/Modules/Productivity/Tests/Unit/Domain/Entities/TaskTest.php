<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\Enums\TaskStatus;
use Modules\Productivity\Domain\Events\TaskCompleted;
use Modules\Productivity\Domain\Events\TaskCreated;
use Modules\Productivity\Domain\Exceptions\TaskAlreadyCompletedException;
use Modules\Productivity\Domain\Exceptions\TaskCannotBeModifiedException;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    public function test_task_can_be_created_with_valid_data(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Complete assignment',
            description: 'Finish the math homework',
            dueDate: new DateTimeImmutable('2026-06-25'),
            priority: PriorityLevel::medium(),
        );

        $this->assertSame('Complete assignment', $task->title());
        $this->assertSame('user-123', $task->userId());
        $this->assertSame(TaskStatus::Pending, $task->status());
        $this->assertFalse($task->isOverdue());

        $events = $task->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(TaskCreated::class, $events[0]);
    }

    public function test_task_can_be_started(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new DateTimeImmutable('2026-06-25'),
            priority: PriorityLevel::medium(),
        );

        $task->start();

        $this->assertTrue($task->status()->isInProgress());
    }

    public function test_task_can_be_completed(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new DateTimeImmutable('2026-06-25'),
            priority: PriorityLevel::medium(),
        );

        $task->complete();

        $this->assertTrue($task->status()->isCompleted());

        $events = $task->releaseEvents();
        $this->assertCount(2, $events);
        $this->assertContainsOnlyInstancesOf(TaskCompleted::class, array_filter($events, fn ($e) => $e instanceof TaskCompleted));
    }

    public function test_task_cannot_be_modified_after_completion(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new DateTimeImmutable('2026-06-25'),
            priority: PriorityLevel::medium(),
        );

        $task->complete();

        $this->expectException(TaskCannotBeModifiedException::class);
        $task->updateTitle('New Title');
    }

    public function test_task_can_be_cancelled(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new DateTimeImmutable('2026-06-25'),
            priority: PriorityLevel::medium(),
        );

        $task->cancel();

        $this->assertTrue($task->status()->isCancelled());
    }

    public function test_task_is_overdue_when_due_date_passed(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new DateTimeImmutable('2020-01-01'),
            priority: PriorityLevel::medium(),
        );

        $this->assertTrue($task->isOverdue());
    }

    public function test_task_without_due_date_is_not_overdue(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: null,
            priority: PriorityLevel::medium(),
        );

        $this->assertFalse($task->isOverdue());
    }

    public function test_task_priority_can_be_updated(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-123',
            title: 'Test Task',
            description: 'Test Description',
            dueDate: new DateTimeImmutable('2026-06-25'),
            priority: PriorityLevel::medium(),
        );

        $task->updatePriority(PriorityLevel::urgent());

        $this->assertTrue($task->priority()->isUrgent());
    }
}
