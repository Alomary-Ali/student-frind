<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\CalendarEventDto;
use Modules\Productivity\Application\DTOs\CreateCalendarEventDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Exceptions\TaskNotFoundException;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\TaskId;

final readonly class CreateCalendarEvent
{
    public function __construct(
        private CalendarEventRepositoryInterface $events,
        private TaskRepositoryInterface $tasks,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(CreateCalendarEventDto $dto): CalendarEventDto
    {
        $linkedTaskId = null;

        if ($dto->linkedTaskId !== null) {
            $taskId = TaskId::fromString($dto->linkedTaskId);
            $task = $this->tasks->findById($taskId)
                ?? throw TaskNotFoundException::forId($dto->linkedTaskId);
            $linkedTaskId = $task->id();
        }

        $event = \Modules\Productivity\Domain\Entities\CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: $dto->userId,
            title: $dto->title,
            description: $dto->description,
            startsAt: new \DateTimeImmutable($dto->startsAt),
            endsAt: new \DateTimeImmutable($dto->endsAt),
            isAllDay: $dto->isAllDay,
            linkedTaskId: $linkedTaskId,
        );

        $this->events->save($event);

        return $this->mapper->toCalendarEventDto($event);
    }
}
