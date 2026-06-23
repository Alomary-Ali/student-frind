<?php

declare(strict_types=1);

namespace Modules\Productivity\Listeners;

use Modules\Academic\Domain\Events\CourseCompleted;
use Modules\Productivity\Application\DTOs\CreateTaskDto;
use Modules\Productivity\Application\UseCases\CreateTask;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;

final readonly class HandleAcademicCourseCompleted
{
    public function __construct(
        private CreateTask $createTask,
    ) {}

    public function handle(CourseCompleted $event): void
    {
        $taskDto = new CreateTaskDto(
            userId: $event->userId,
            title: "Update academic progress after course completion",
            description: "Update your academic goals and progress tracking after completing course {$event->courseId}",
            dueDate: now()->addDays(3)->format('Y-m-d H:i:s'),
            priority: PriorityLevel::low()->value(),
            linkedGoalId: null,
        );

        $this->createTask->execute($taskDto);
    }
}
