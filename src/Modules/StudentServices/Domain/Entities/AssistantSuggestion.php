<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class AssistantSuggestion
{
    private function __construct(
        private readonly string $id,
        private readonly string $conversationId,
        private readonly string $messageId,
        private readonly string $suggestionType,
        private string $title,
        private ?string $actionUrl,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        string $conversationId,
        string $messageId,
        string $suggestionType,
        string $title,
        ?string $actionUrl = null,
    ): self {
        return new self(
            Uuid::uuid4()->toString(),
            $conversationId,
            $messageId,
            $suggestionType,
            $title,
            $actionUrl,
            new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        string $id,
        string $conversationId,
        string $messageId,
        string $suggestionType,
        string $title,
        ?string $actionUrl,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            $id,
            $conversationId,
            $messageId,
            $suggestionType,
            $title,
            $actionUrl,
            $createdAt,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function conversationId(): string
    {
        return $this->conversationId;
    }

    public function messageId(): string
    {
        return $this->messageId;
    }

    public function suggestionType(): string
    {
        return $this->suggestionType;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function actionUrl(): ?string
    {
        return $this->actionUrl;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
