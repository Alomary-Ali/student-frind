<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class FAQ
{
    private function __construct(
        private readonly string $id,
        private readonly string $categoryId,
        private string $question,
        private string $answer,
        private int $sortOrder,
        private bool $isActive,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        string $categoryId,
        string $question,
        string $answer,
        int $sortOrder = 0,
        bool $isActive = true,
    ): self {
        $now = new DateTimeImmutable;

        return new self(
            Uuid::uuid4()->toString(),
            $categoryId,
            $question,
            $answer,
            $sortOrder,
            $isActive,
            $now,
            $now,
        );
    }

    public static function reconstitute(
        string $id,
        string $categoryId,
        string $question,
        string $answer,
        int $sortOrder,
        bool $isActive,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $categoryId,
            $question,
            $answer,
            $sortOrder,
            $isActive,
            $createdAt,
            $updatedAt,
        );
    }

    public function update(string $question, string $answer, int $sortOrder, bool $isActive): void
    {
        $this->question = $question;
        $this->answer = $answer;
        $this->sortOrder = $sortOrder;
        $this->isActive = $isActive;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function question(): string
    {
        return $this->question;
    }

    public function answer(): string
    {
        return $this->answer;
    }

    public function sortOrder(): int
    {
        return $this->sortOrder;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
