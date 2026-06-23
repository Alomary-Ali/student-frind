<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Entities;

use DateTimeImmutable;
use Modules\Notifications\Domain\Enums\NotificationChannel;
use Modules\Notifications\Domain\Enums\NotificationType;
use Modules\Notifications\Domain\Events\NotificationCreated;
use Modules\Notifications\Domain\ValueObjects\NotificationId;

final class Notification
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly NotificationId $id,
        private readonly string $studentId,
        private readonly NotificationType $type,
        private readonly string $title,
        private readonly string $message,
        private readonly NotificationChannel $channel,
        private readonly ?string $link,
        private bool $isRead,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        NotificationId $id,
        string $studentId,
        NotificationType $type,
        string $title,
        string $message,
        NotificationChannel $channel,
        ?string $link = null,
    ): self {
        $now = new DateTimeImmutable;

        $notification = new self(
            $id,
            $studentId,
            $type,
            $title,
            $message,
            $channel,
            $link,
            false,
            $now,
            $now,
        );

        $notification->raise(new NotificationCreated(
            id: $id->value(),
            studentId: $studentId,
            type: $type->value,
            title: $title,
            message: $message,
            channel: $channel->value,
            createdAt: $now,
        ));

        return $notification;
    }

    public static function reconstitute(
        NotificationId $id,
        string $studentId,
        NotificationType $type,
        string $title,
        string $message,
        NotificationChannel $channel,
        ?string $link,
        bool $isRead,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $type,
            $title,
            $message,
            $channel,
            $link,
            $isRead,
            $createdAt,
            $updatedAt,
        );
    }

    public function markAsRead(): void
    {
        $this->isRead = true;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function id(): NotificationId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function type(): NotificationType
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function channel(): NotificationChannel
    {
        return $this->channel;
    }

    public function link(): ?string
    {
        return $this->link;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
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
