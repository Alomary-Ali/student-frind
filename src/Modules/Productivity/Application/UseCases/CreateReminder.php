<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\CreateReminderDto;
use Modules\Productivity\Application\DTOs\ReminderDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\Exceptions\TaskNotFoundException;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateReminder
{
    public function __construct(
        private ReminderRepositoryInterface $reminders,
        private TaskRepositoryInterface $tasks,
        private EventDispatcherInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(CreateReminderDto $dto): ReminderDto
    {
        $linkedTaskId = null;

        if ($dto->linkedTaskId !== null) {
            $taskId = TaskId::fromString($dto->linkedTaskId);
            $task = $this->tasks->findById($taskId)
                ?? throw TaskNotFoundException::forId($dto->linkedTaskId);
            $linkedTaskId = $task->id();
        }

        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: $dto->userId,
            message: $dto->message,
            triggerAt: new \DateTimeImmutable($dto->triggerAt),
            type: ReminderType::from($dto->type),
            linkedTaskId: $linkedTaskId,
        );

        $this->reminders->save($reminder);
        $this->events->dispatch($reminder->releaseEvents());

        return $this->mapper->toReminderDto($reminder);
    }
}
