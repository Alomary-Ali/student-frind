<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Application\DTOs;

use Modules\Productivity\Application\DTOs\AssignmentDto;
use Modules\Productivity\Application\DTOs\CalendarEventDto;
use Modules\Productivity\Application\DTOs\CreateAssignmentDto;
use Modules\Productivity\Application\DTOs\CreateCalendarEventDto;
use Modules\Productivity\Application\DTOs\CreateExamDto;
use Modules\Productivity\Application\DTOs\CreateGoalDto;
use Modules\Productivity\Application\DTOs\CreateProjectDto;
use Modules\Productivity\Application\DTOs\CreateReminderDto;
use Modules\Productivity\Application\DTOs\CreateTaskDto;
use Modules\Productivity\Application\DTOs\ExamDto;
use Modules\Productivity\Application\DTOs\GoalDto;
use Modules\Productivity\Application\DTOs\ProductivityDashboardDto;
use Modules\Productivity\Application\DTOs\ProductivitySnapshotDto;
use Modules\Productivity\Application\DTOs\ProjectDto;
use Modules\Productivity\Application\DTOs\ReminderDto;
use Modules\Productivity\Application\DTOs\TaskDto;
use PHPUnit\Framework\TestCase;

final class ProductivityDtoTest extends TestCase
{
    public function test_create_goal_dto(): void
    {
        $dto = new CreateGoalDto(
            userId: 'user-1',
            title: 'Test Goal',
            description: 'Description',
            targetDate: '2026-12-31',
            priority: 'high',
        );

        $this->assertSame('user-1', $dto->userId);
        $this->assertSame('Test Goal', $dto->title);
        $this->assertSame('Description', $dto->description);
        $this->assertSame('2026-12-31', $dto->targetDate);
        $this->assertSame('high', $dto->priority);
    }

    public function test_goal_dto(): void
    {
        $dto = new GoalDto(
            id: 'goal-1',
            userId: 'user-1',
            title: 'Test Goal',
            description: 'Desc',
            targetDate: '2026-12-31',
            priority: 'high',
            progress: 50.0,
            status: 'active',
            goalType: 'semester',
            createdAt: '2026-06-23 12:00:00',
            isOverdue: false,
        );

        $this->assertSame('goal-1', $dto->id);
        $this->assertSame('user-1', $dto->userId);
        $this->assertSame('Test Goal', $dto->title);
        $this->assertSame(50.0, $dto->progress);
        $this->assertSame('active', $dto->status);
        $this->assertSame('semester', $dto->goalType);
        $this->assertFalse($dto->isOverdue);
    }

    public function test_create_task_dto(): void
    {
        $dto = new CreateTaskDto(
            userId: 'user-1',
            title: 'Test Task',
            description: 'Desc',
            dueDate: '2026-07-01',
            priority: 'medium',
            linkedGoalId: null,
        );

        $this->assertSame('Test Task', $dto->title);
        $this->assertNull($dto->linkedGoalId);
    }

    public function test_create_task_dto_with_linked_goal(): void
    {
        $dto = new CreateTaskDto(
            userId: 'user-1',
            title: 'Test Task',
            description: 'Desc',
            dueDate: '2026-07-01',
            priority: 'medium',
            linkedGoalId: 'goal-1',
        );

        $this->assertSame('goal-1', $dto->linkedGoalId);
    }

    public function test_task_dto(): void
    {
        $dto = new TaskDto(
            id: 'task-1',
            userId: 'user-1',
            title: 'Test Task',
            description: 'Desc',
            dueDate: '2026-07-01',
            priority: 'high',
            status: 'pending',
            linkedGoalId: null,
            createdAt: '2026-06-23 12:00:00',
            completedAt: null,
            isOverdue: false,
        );

        $this->assertSame('task-1', $dto->id);
        $this->assertSame('pending', $dto->status);
        $this->assertNull($dto->completedAt);
    }

    public function test_create_project_dto(): void
    {
        $dto = new CreateProjectDto(
            userId: 'user-1',
            title: 'Test Project',
            description: 'Desc',
            startDate: '2026-01-01',
            dueDate: '2026-12-31',
        );

        $this->assertSame('Test Project', $dto->title);
        $this->assertSame('2026-01-01', $dto->startDate);
        $this->assertSame('2026-12-31', $dto->dueDate);
    }

    public function test_project_dto(): void
    {
        $dto = new ProjectDto(
            id: 'proj-1',
            userId: 'user-1',
            title: 'Test Project',
            description: 'Desc',
            startDate: '2026-01-01',
            dueDate: '2026-12-31',
            status: 'planning',
            progressPercentage: 0,
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-01 00:00:00',
        );

        $this->assertSame('proj-1', $dto->id);
        $this->assertSame('planning', $dto->status);
        $this->assertSame(0, $dto->progressPercentage);
    }

    public function test_create_exam_dto(): void
    {
        $dto = new CreateExamDto(
            userId: 'user-1',
            courseId: 'CS101',
            title: 'Midterm',
            examType: 'midterm',
            examDate: '2026-06-15',
            location: 'Room 101',
        );

        $this->assertSame('CS101', $dto->courseId);
        $this->assertSame('midterm', $dto->examType);
        $this->assertSame('Room 101', $dto->location);
    }

    public function test_exam_dto(): void
    {
        $dto = new ExamDto(
            id: 'exam-1',
            userId: 'user-1',
            courseId: 'CS101',
            title: 'Final',
            examType: 'final',
            examDate: '2026-06-20',
            location: 'Hall A',
            status: 'scheduled',
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-01 00:00:00',
        );

        $this->assertSame('exam-1', $dto->id);
        $this->assertSame('final', $dto->examType);
        $this->assertSame('scheduled', $dto->status);
    }

    public function test_create_assignment_dto(): void
    {
        $dto = new CreateAssignmentDto(
            userId: 'user-1',
            courseId: 'CS101',
            title: 'HW1',
            description: 'Desc',
            dueDate: '2026-07-01',
        );

        $this->assertSame('HW1', $dto->title);
        $this->assertSame('CS101', $dto->courseId);
    }

    public function test_assignment_dto(): void
    {
        $dto = new AssignmentDto(
            id: 'assign-1',
            userId: 'user-1',
            courseId: 'CS101',
            title: 'HW1',
            description: 'Desc',
            assignedAt: '2026-06-01 00:00:00',
            dueDate: '2026-07-01',
            status: 'assigned',
            grade: null,
            submissionUrl: null,
            createdAt: '2026-06-01 00:00:00',
            updatedAt: '2026-06-01 00:00:00',
        );

        $this->assertSame('assign-1', $dto->id);
        $this->assertSame('assigned', $dto->status);
        $this->assertNull($dto->grade);
    }

    public function test_create_calendar_event_dto(): void
    {
        $dto = new CreateCalendarEventDto(
            userId: 'user-1',
            title: 'Study',
            description: 'Desc',
            startsAt: '2026-07-01 10:00:00',
            endsAt: '2026-07-01 12:00:00',
            isAllDay: false,
            linkedTaskId: null,
        );

        $this->assertSame('Study', $dto->title);
        $this->assertFalse($dto->isAllDay);
    }

    public function test_calendar_event_dto(): void
    {
        $dto = new CalendarEventDto(
            id: 'event-1',
            userId: 'user-1',
            title: 'Study',
            description: 'Desc',
            startsAt: '2026-07-01 10:00:00',
            endsAt: '2026-07-01 12:00:00',
            isAllDay: false,
            linkedTaskId: null,
            createdAt: '2026-06-23 12:00:00',
            isPast: false,
            isFuture: true,
            isOngoing: false,
        );

        $this->assertSame('event-1', $dto->id);
        $this->assertTrue($dto->isFuture);
        $this->assertFalse($dto->isPast);
    }

    public function test_create_reminder_dto(): void
    {
        $dto = new CreateReminderDto(
            userId: 'user-1',
            message: 'Reminder',
            triggerAt: '2026-07-01 09:00:00',
            type: 'in_app',
            linkedTaskId: null,
        );

        $this->assertSame('Reminder', $dto->message);
        $this->assertSame('in_app', $dto->type);
    }

    public function test_reminder_dto(): void
    {
        $dto = new ReminderDto(
            id: 'rem-1',
            userId: 'user-1',
            message: 'Reminder',
            triggerAt: '2026-07-01 09:00:00',
            type: 'in_app',
            linkedTaskId: null,
            status: 'pending',
            createdAt: '2026-06-23 12:00:00',
            triggeredAt: null,
            isDue: false,
        );

        $this->assertSame('rem-1', $dto->id);
        $this->assertSame('pending', $dto->status);
        $this->assertNull($dto->triggeredAt);
    }

    public function test_productivity_dashboard_dto(): void
    {
        $dto = new ProductivityDashboardDto(
            userId: 'user-1',
            activeGoals: 3,
            completedGoals: 1,
            pendingTasks: 5,
            inProgressTasks: 2,
            completedTasks: 10,
            overdueTasks: 1,
            upcomingReminders: 2,
            overallCompletionRate: 65.0,
            recentTasks: [],
            upcomingEvents: [],
        );

        $this->assertSame('user-1', $dto->userId);
        $this->assertSame(3, $dto->activeGoals);
        $this->assertSame(5, $dto->pendingTasks);
        $this->assertSame(65.0, $dto->overallCompletionRate);
    }

    public function test_productivity_snapshot_dto(): void
    {
        $dto = new ProductivitySnapshotDto(
            id: 'snap-1',
            userId: 'user-1',
            totalGoals: 5,
            completedGoals: 2,
            totalTasks: 20,
            completedTasks: 10,
            overdueTasks: 3,
            completionRate: 50.0,
            snapshotDate: '2026-06-23',
            createdAt: '2026-06-23 12:00:00',
        );

        $this->assertSame('snap-1', $dto->id);
        $this->assertSame(5, $dto->totalGoals);
        $this->assertSame(10, $dto->completedTasks);
        $this->assertSame(50.0, $dto->completionRate);
    }
}
