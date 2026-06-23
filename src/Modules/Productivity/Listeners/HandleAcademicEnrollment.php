<?php

declare(strict_types=1);

namespace Modules\Productivity\Listeners;

use Modules\Academic\Domain\Events\StudentEnrolled;
use Modules\Productivity\Application\DTOs\CreateReminderDto;
use Modules\Productivity\Application\DTOs\CreateTaskDto;
use Modules\Productivity\Application\UseCases\CreateReminder;
use Modules\Productivity\Application\UseCases\CreateTask;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;

final readonly class HandleAcademicEnrollment
{
    public function __construct(
        private CreateTask $createTask,
        private CreateReminder $createReminder,
    ) {}

    public function handle(StudentEnrolled $event): void
    {
        $taskDto = new CreateTaskDto(
            userId: $event->userId,
            title: "Review course enrollment for {$event->courseId}",
            description: 'Review your enrollment details and prepare for the upcoming semester',
            dueDate: null,
            priority: PriorityLevel::medium()->value(),
            linkedGoalId: null,
        );

        $this->createTask->execute($taskDto);

        $reminderDto = new CreateReminderDto(
            userId: $event->userId,
            message: "Don't forget to review your course schedule for the upcoming semester",
            triggerAt: now()->addDays(7)->format('Y-m-d H:i:s'),
            type: 'in_app',
            linkedTaskId: null,
        );

        $this->createReminder->execute($reminderDto);
    }
}
