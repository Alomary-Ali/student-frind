<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\ConversationStatus;
use Modules\StudentServices\Domain\Events\ConversationStarted;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;

final class AssistantConversation
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly ConversationId $id,
        private readonly string $studentId,
        private ?string $title,
        private ConversationStatus $status,
        private array $contextData,
        private DateTimeImmutable $lastActivityAt,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        ConversationId $id,
        string $studentId,
        ?string $title = null,
        array $contextData = [],
    ): self {
        $now = new DateTimeImmutable;

        $conversation = new self(
            $id,
            $studentId,
            $title,
            ConversationStatus::ACTIVE,
            $contextData,
            $now,
            $now,
            $now,
        );

        $conversation->raise(new ConversationStarted(
            $id->value(),
            $studentId,
            $now,
        ));

        return $conversation;
    }

    public static function reconstitute(
        ConversationId $id,
        string $studentId,
        ?string $title,
        ConversationStatus $status,
        array $contextData,
        DateTimeImmutable $lastActivityAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $title,
            $status,
            $contextData,
            $lastActivityAt,
            $createdAt,
            $updatedAt,
        );
    }

    public function close(): void
    {
        $this->status = ConversationStatus::CLOSED;
        $this->updatedAt = new DateTimeImmutable;
        $this->lastActivityAt = new DateTimeImmutable;
    }

    public function archive(): void
    {
        $this->status = ConversationStatus::ARCHIVED;
        $this->updatedAt = new DateTimeImmutable;
        $this->lastActivityAt = new DateTimeImmutable;
    }

    public function updateContext(array $data): void
    {
        $this->contextData = array_merge($this->contextData, $data);
        $this->updatedAt = new DateTimeImmutable;
        $this->lastActivityAt = new DateTimeImmutable;
    }

    public function touch(): void
    {
        $this->lastActivityAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function id(): ConversationId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function status(): ConversationStatus
    {
        return $this->status;
    }

    public function contextData(): array
    {
        return $this->contextData;
    }

    public function lastActivityAt(): DateTimeImmutable
    {
        return $this->lastActivityAt;
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
