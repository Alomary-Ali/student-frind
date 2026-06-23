<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Application\Mappers;

use DateTimeImmutable;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Entities\CalendarEvent;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\Enums\GoalType;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use PHPUnit\Framework\TestCase;

final class ProductivityMapperTest extends TestCase
{
    private ProductivityMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new ProductivityMapper();
    }

    public function test_maps_goal_to_dto(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-1',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::high(),
            goalType: GoalType::Semester,
        );

        $dto = $this->mapper->toGoalDto($goal);

        $this->assertSame($goal->id()->value(), $dto->id);
        $this->assertSame('user-1', $dto->userId);
        $this->assertSame('Test Goal', $dto->title);
        $this->assertSame('high', $dto->priority);
        $this->assertSame(0.0, $dto->progress);
        $this->assertSame('active', $dto->status);
        $this->assertSame('semester', $dto->goalType);
    }

    public function test_maps_task_to_dto(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: 'user-1',
            title: 'Test Task',
            description: 'Test Desc',
            dueDate: new DateTimeImmutable('2026-07-01'),
            priority: PriorityLevel::medium(),
        );

        $dto = $this->mapper->toTaskDto($task);

        $this->assertSame($task->id()->value(), $dto->id);
        $this->assertSame('Test Task', $dto->title);
        $this->assertSame('medium', $dto->priority);
        $this->assertSame('pending', $dto->status);
    }

    public function test_maps_reminder_to_dto(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test Reminder',
            triggerAt: new DateTimeImmutable('2026-07-01 09:00:00'),
            type: ReminderType::InApp,
        );

        $dto = $this->mapper->toReminderDto($reminder);

        $this->assertSame($reminder->id()->value(), $dto->id);
        $this->assertSame('Test Reminder', $dto->message);
        $this->assertSame('in_app', $dto->type);
        $this->assertSame('pending', $dto->status);
    }

    public function test_maps_calendar_event_to_dto(): void
    {
        $event = CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: 'user-1',
            title: 'Test Event',
            description: 'Test Desc',
            startsAt: new DateTimeImmutable('2026-07-01 10:00:00'),
            endsAt: new DateTimeImmutable('2026-07-01 12:00:00'),
            isAllDay: false,
        );

        $dto = $this->mapper->toCalendarEventDto($event);

        $this->assertSame($event->id()->value(), $dto->id);
        $this->assertSame('Test Event', $dto->title);
        $this->assertFalse($dto->isAllDay);
    }

    public function test_maps_goal_list_to_dto_list(): void
    {
        $goals = [
            Goal::create(
                id: GoalId::generate(),
                userId: 'user-1',
                title: 'Goal 1',
                description: 'Desc 1',
                targetDate: new DateTimeImmutable('2026-12-31'),
                priority: PriorityLevel::low(),
            ),
            Goal::create(
                id: GoalId::generate(),
                userId: 'user-1',
                title: 'Goal 2',
                description: 'Desc 2',
                targetDate: new DateTimeImmutable('2026-12-31'),
                priority: PriorityLevel::high(),
            ),
        ];

        $dtos = $this->mapper->toGoalDtoList($goals);

        $this->assertCount(2, $dtos);
        $this->assertSame('Goal 1', $dtos[0]->title);
        $this->assertSame('Goal 2', $dtos[1]->title);
    }

    public function test_maps_task_list_to_dto_list(): void
    {
        $tasks = [
            Task::create(
                id: TaskId::generate(),
                userId: 'user-1',
                title: 'Task 1',
                description: 'Desc',
                dueDate: null,
                priority: PriorityLevel::low(),
            ),
        ];

        $dtos = $this->mapper->toTaskDtoList($tasks);

        $this->assertCount(1, $dtos);
        $this->assertSame('Task 1', $dtos[0]->title);
    }

    public function test_maps_empty_lists(): void
    {
        $this->assertEmpty($this->mapper->toGoalDtoList([]));
        $this->assertEmpty($this->mapper->toTaskDtoList([]));
        $this->assertEmpty($this->mapper->toReminderDtoList([]));
        $this->assertEmpty($this->mapper->toCalendarEventDtoList([]));
    }
}
