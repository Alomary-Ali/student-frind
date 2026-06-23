<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\Mappers;

use Modules\Productivity\Application\DTOs\CalendarEventDto;
use Modules\Productivity\Application\DTOs\GoalDto;
use Modules\Productivity\Application\DTOs\ReminderDto;
use Modules\Productivity\Application\DTOs\TaskDto;
use Modules\Productivity\Domain\Entities\CalendarEvent;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Entities\Task;

final class ProductivityMapper
{
    public function toGoalDto(Goal $goal): GoalDto
    {
        return new GoalDto(
            id: $goal->id()->value(),
            userId: $goal->userId(),
            title: $goal->title(),
            description: $goal->description(),
            targetDate: $goal->targetDate()->format('Y-m-d'),
            priority: $goal->priority()->value(),
            progress: $goal->progress()->value(),
            status: $goal->status()->value,
            goalType: $goal->goalType()->value,
            createdAt: $goal->createdAt()->format('Y-m-d H:i:s'),
            isOverdue: $goal->isOverdue(),
        );
    }

    public function toTaskDto(Task $task): TaskDto
    {
        return new TaskDto(
            id: $task->id()->value(),
            userId: $task->userId(),
            title: $task->title(),
            description: $task->description(),
            dueDate: $task->dueDate()?->format('Y-m-d H:i:s'),
            priority: $task->priority()->value(),
            status: $task->status()->value,
            linkedGoalId: $task->linkedGoalId()?->value(),
            createdAt: $task->createdAt()->format('Y-m-d H:i:s'),
            completedAt: $task->completedAt()?->format('Y-m-d H:i:s'),
            isOverdue: $task->isOverdue(),
        );
    }

    public function toReminderDto(Reminder $reminder): ReminderDto
    {
        return new ReminderDto(
            id: $reminder->id()->value(),
            userId: $reminder->userId(),
            message: $reminder->message(),
            triggerAt: $reminder->triggerAt()->format('Y-m-d H:i:s'),
            type: $reminder->type()->value,
            linkedTaskId: $reminder->linkedTaskId()?->value(),
            status: $reminder->status()->value,
            createdAt: $reminder->createdAt()->format('Y-m-d H:i:s'),
            triggeredAt: $reminder->triggeredAt()?->format('Y-m-d H:i:s'),
            isDue: $reminder->isDue(),
        );
    }

    public function toCalendarEventDto(CalendarEvent $event): CalendarEventDto
    {
        return new CalendarEventDto(
            id: $event->id()->value(),
            userId: $event->userId(),
            title: $event->title(),
            description: $event->description(),
            startsAt: $event->startsAt()->format('Y-m-d H:i:s'),
            endsAt: $event->endsAt()->format('Y-m-d H:i:s'),
            isAllDay: $event->isAllDay(),
            linkedTaskId: $event->linkedTaskId()?->value(),
            createdAt: $event->createdAt()->format('Y-m-d H:i:s'),
            isPast: $event->isPast(),
            isFuture: $event->isFuture(),
            isOngoing: $event->isOngoing(),
        );
    }

    /** @return list<GoalDto> */
    public function toGoalDtoList(array $goals): array
    {
        return array_map(fn (Goal $goal) => $this->toGoalDto($goal), $goals);
    }

    /** @return list<TaskDto> */
    public function toTaskDtoList(array $tasks): array
    {
        return array_map(fn (Task $task) => $this->toTaskDto($task), $tasks);
    }

    /** @return list<ReminderDto> */
    public function toReminderDtoList(array $reminders): array
    {
        return array_map(fn (Reminder $reminder) => $this->toReminderDto($reminder), $reminders);
    }

    /** @return list<CalendarEventDto> */
    public function toCalendarEventDtoList(array $events): array
    {
        return array_map(fn (CalendarEvent $event) => $this->toCalendarEventDto($event), $events);
    }
}
