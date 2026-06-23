<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\MessageRole;
use Modules\StudentServices\Domain\ValueObjects\MessageId;

final class AssistantMessage
{
    private function __construct(
        private readonly MessageId $id,
        private readonly string $conversationId,
        private readonly MessageRole $role,
        private string $content,
        private array $metadata,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        MessageId $id,
        string $conversationId,
        MessageRole $role,
        string $content,
        array $metadata = [],
    ): self {
        return new self(
            $id,
            $conversationId,
            $role,
            $content,
            $metadata,
            new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        MessageId $id,
        string $conversationId,
        MessageRole $role,
        string $content,
        array $metadata,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            $id,
            $conversationId,
            $role,
            $content,
            $metadata,
            $createdAt,
        );
    }

    public function id(): MessageId
    {
        return $this->id;
    }

    public function conversationId(): string
    {
        return $this->conversationId;
    }

    public function role(): MessageRole
    {
        return $this->role;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
