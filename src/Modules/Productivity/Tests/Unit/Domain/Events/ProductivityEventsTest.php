<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Events;

use DateTimeImmutable;
use Modules\Productivity\Domain\Events\AssignmentCreated;
use Modules\Productivity\Domain\Events\ExamCreated;
use Modules\Productivity\Domain\Events\GoalCompleted;
use Modules\Productivity\Domain\Events\GoalCreated;
use Modules\Productivity\Domain\Events\ProductivitySnapshotGenerated;
use Modules\Productivity\Domain\Events\ProjectCreated;
use Modules\Productivity\Domain\Events\ReminderCreated;
use Modules\Productivity\Domain\Events\ReminderTriggered;
use Modules\Productivity\Domain\Events\TaskCompleted;
use Modules\Productivity\Domain\Events\TaskCreated;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Productivity\Domain\ValueObjects\ProjectId;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class ProductivityEventsTest extends TestCase
{
    public function test_goal_created_event(): void
    {
        $event = new GoalCreated(
            goalId: 'goal-1',
            userId: 'user-1',
            title: 'Test Goal',
            targetDate: '2026-12-31',
            priority: 'high',
            occurredAt: new DateTimeImmutable,
        );

        $this->assertSame('goal-1', $event->goalId);
        $this->assertSame('user-1', $event->userId);
        $this->assertSame('Test Goal', $event->title);
        $this->assertSame('2026-12-31', $event->targetDate);
        $this->assertSame('high', $event->priority);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredAt);
    }

    public function test_goal_completed_event(): void
    {
        $event = new GoalCompleted(
            goalId: 'goal-1',
            userId: 'user-1',
            title: 'Test Goal',
            completedAt: new DateTimeImmutable,
        );

        $this->assertSame('goal-1', $event->goalId);
        $this->assertSame('user-1', $event->userId);
        $this->assertSame('Test Goal', $event->title);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->completedAt);
    }

    public function test_task_created_event(): void
    {
        $event = new TaskCreated(
            taskId: 'task-1',
            userId: 'user-1',
            title: 'Test Task',
            dueDate: '2026-06-25',
            priority: 'medium',
            linkedGoalId: null,
            occurredAt: new DateTimeImmutable,
        );

        $this->assertSame('task-1', $event->taskId);
        $this->assertSame('Test Task', $event->title);
        $this->assertSame('2026-06-25', $event->dueDate);
        $this->assertNull($event->linkedGoalId);
    }

    public function test_task_completed_event(): void
    {
        $event = new TaskCompleted(
            taskId: 'task-1',
            userId: 'user-1',
            title: 'Test Task',
            linkedGoalId: 'goal-1',
            completedAt: new DateTimeImmutable,
        );

        $this->assertSame('task-1', $event->taskId);
        $this->assertSame('goal-1', $event->linkedGoalId);
    }

    public function test_project_created_event(): void
    {
        $projectId = ProjectId::generate();
        $userId = UserId::generate();
        $event = new ProjectCreated(
            projectId: $projectId,
            userId: $userId,
            title: 'Test Project',
            startDate: new DateTimeImmutable('2026-01-01'),
            dueDate: new DateTimeImmutable('2026-12-31'),
        );

        $this->assertSame($projectId, $event->projectId);
        $this->assertSame($userId, $event->userId);
        $this->assertSame('Test Project', $event->title);
    }

    public function test_exam_created_event(): void
    {
        $examId = ExamId::generate();
        $userId = UserId::generate();
        $event = new ExamCreated(
            examId: $examId,
            userId: $userId,
            courseId: 'CS101',
            title: 'Midterm Exam',
            examDate: new DateTimeImmutable('2026-06-15'),
        );

        $this->assertSame($examId, $event->examId);
        $this->assertSame('CS101', $event->courseId);
        $this->assertSame('Midterm Exam', $event->title);
    }

    public function test_assignment_created_event(): void
    {
        $assignmentId = AssignmentId::generate();
        $userId = UserId::generate();
        $event = new AssignmentCreated(
            assignmentId: $assignmentId,
            userId: $userId,
            courseId: 'CS101',
            title: 'Homework 1',
            dueDate: new DateTimeImmutable('2026-06-20'),
        );

        $this->assertSame($assignmentId, $event->assignmentId);
        $this->assertSame('CS101', $event->courseId);
        $this->assertSame('Homework 1', $event->title);
    }

    public function test_reminder_created_event(): void
    {
        $event = new ReminderCreated(
            reminderId: 'reminder-1',
            userId: 'user-1',
            message: 'Task due tomorrow',
            triggerAt: '2026-06-24 09:00:00',
            type: 'in_app',
            linkedTaskId: 'task-1',
            occurredAt: new DateTimeImmutable,
        );

        $this->assertSame('reminder-1', $event->reminderId);
        $this->assertSame('Task due tomorrow', $event->message);
        $this->assertSame('task-1', $event->linkedTaskId);
    }

    public function test_reminder_triggered_event(): void
    {
        $event = new ReminderTriggered(
            reminderId: 'reminder-1',
            userId: 'user-1',
            message: 'Task due tomorrow',
            type: 'in_app',
            triggeredAt: new DateTimeImmutable,
        );

        $this->assertSame('reminder-1', $event->reminderId);
        $this->assertSame('Task due tomorrow', $event->message);
    }

    public function test_snapshot_generated_event(): void
    {
        $event = new ProductivitySnapshotGenerated(
            snapshotId: 'snapshot-1',
            userId: 'user-1',
            totalGoals: 5,
            completedGoals: 2,
            totalTasks: 20,
            completedTasks: 10,
            overdueTasks: 3,
            completionRate: 50.0,
            snapshotDate: '2026-06-23',
            occurredAt: new DateTimeImmutable,
        );

        $this->assertSame('snapshot-1', $event->snapshotId);
        $this->assertSame(5, $event->totalGoals);
        $this->assertSame(10, $event->completedTasks);
        $this->assertSame(50.0, $event->completionRate);
    }
}
