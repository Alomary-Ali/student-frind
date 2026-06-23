<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Enums\ReminderStatus;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\Events\ReminderCreated;
use Modules\Productivity\Domain\Events\ReminderTriggered;
use Modules\Productivity\Domain\Exceptions\ReminderAlreadyTriggeredException;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\TaskId;

final class Reminder
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly ReminderId $id,
        private readonly string $userId,
        private readonly string $message,
        private readonly DateTimeImmutable $triggerAt,
        private readonly ReminderType $type,
        private readonly ?TaskId $linkedTaskId,
        private ReminderStatus $status,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $triggeredAt,
    ) {}

    public static function create(
        ReminderId $id,
        string $userId,
        string $message,
        DateTimeImmutable $triggerAt,
        ReminderType $type,
        ?TaskId $linkedTaskId = null,
    ): self {
        $reminder = new self(
            id: $id,
            userId: $userId,
            message: $message,
            triggerAt: $triggerAt,
            type: $type,
            linkedTaskId: $linkedTaskId,
            status: ReminderStatus::Pending,
            createdAt: new DateTimeImmutable,
            triggeredAt: null,
        );

        $reminder->raise(new ReminderCreated(
            reminderId: $id->value(),
            userId: $userId,
            message: $message,
            triggerAt: $triggerAt->format('Y-m-d H:i:s'),
            type: $type->value,
            linkedTaskId: $linkedTaskId?->value(),
            occurredAt: new DateTimeImmutable,
        ));

        return $reminder;
    }

    public static function reconstitute(
        ReminderId $id,
        string $userId,
        string $message,
        DateTimeImmutable $triggerAt,
        ReminderType $type,
        ?TaskId $linkedTaskId,
        ReminderStatus $status,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $triggeredAt,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            message: $message,
            triggerAt: $triggerAt,
            type: $type,
            linkedTaskId: $linkedTaskId,
            status: $status,
            createdAt: $createdAt,
            triggeredAt: $triggeredAt,
        );
    }

    public function trigger(): void
    {
        if ($this->status->isTriggered()) {
            throw ReminderAlreadyTriggeredException::forReminder($this->id->value());
        }

        $this->status = ReminderStatus::Triggered;

        $this->raise(new ReminderTriggered(
            reminderId: $this->id->value(),
            userId: $this->userId,
            message: $this->message,
            type: $this->type->value,
            triggeredAt: new DateTimeImmutable,
        ));
    }

    public function dismiss(): void
    {
        $this->status = ReminderStatus::Dismissed;
    }

    public function id(): ReminderId
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function triggerAt(): DateTimeImmutable
    {
        return $this->triggerAt;
    }

    public function type(): ReminderType
    {
        return $this->type;
    }

    public function linkedTaskId(): ?TaskId
    {
        return $this->linkedTaskId;
    }

    public function status(): ReminderStatus
    {
        return $this->status;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function triggeredAt(): ?DateTimeImmutable
    {
        return $this->triggeredAt;
    }

    public function isDue(): bool
    {
        return $this->triggerAt <= new DateTimeImmutable && $this->status->isPending();
    }

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
