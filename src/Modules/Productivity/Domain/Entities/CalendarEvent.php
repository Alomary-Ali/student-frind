<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\TaskId;

final class CalendarEvent
{
    private function __construct(
        private readonly CalendarEventId $id,
        private readonly string $userId,
        private readonly string $title,
        private readonly string $description,
        private readonly DateTimeImmutable $startsAt,
        private readonly DateTimeImmutable $endsAt,
        private readonly bool $isAllDay,
        private readonly ?TaskId $linkedTaskId,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        CalendarEventId $id,
        string $userId,
        string $title,
        string $description,
        DateTimeImmutable $startsAt,
        DateTimeImmutable $endsAt,
        bool $isAllDay = false,
        ?TaskId $linkedTaskId = null,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            title: $title,
            description: $description,
            startsAt: $startsAt,
            endsAt: $endsAt,
            isAllDay: $isAllDay,
            linkedTaskId: $linkedTaskId,
            createdAt: new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        CalendarEventId $id,
        string $userId,
        string $title,
        string $description,
        DateTimeImmutable $startsAt,
        DateTimeImmutable $endsAt,
        bool $isAllDay,
        ?TaskId $linkedTaskId,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            title: $title,
            description: $description,
            startsAt: $startsAt,
            endsAt: $endsAt,
            isAllDay: $isAllDay,
            linkedTaskId: $linkedTaskId,
            createdAt: $createdAt,
        );
    }

    public function id(): CalendarEventId
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startsAt(): DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function endsAt(): DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function isAllDay(): bool
    {
        return $this->isAllDay;
    }

    public function linkedTaskId(): ?TaskId
    {
        return $this->linkedTaskId;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isPast(): bool
    {
        return $this->endsAt < new DateTimeImmutable;
    }

    public function isFuture(): bool
    {
        return $this->startsAt > new DateTimeImmutable;
    }

    public function isOngoing(): bool
    {
        $now = new DateTimeImmutable;

        return $this->startsAt <= $now && $this->endsAt >= $now;
    }
}
