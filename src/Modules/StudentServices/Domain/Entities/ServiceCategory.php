<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\ServiceCategoryType;
use Ramsey\Uuid\Uuid;

final class ServiceCategory
{
    private function __construct(
        private readonly string $id,
        private string $name,
        private ServiceCategoryType $type,
        private string $description,
        private bool $isActive,
        private int $sortOrder,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        string $name,
        ServiceCategoryType $type,
        string $description,
        int $sortOrder = 0,
    ): self {
        $now = new DateTimeImmutable;

        return new self(
            Uuid::uuid4()->toString(),
            $name,
            $type,
            $description,
            true,
            $sortOrder,
            $now,
            $now,
        );
    }

    public static function reconstitute(
        string $id,
        string $name,
        ServiceCategoryType $type,
        string $description,
        bool $isActive,
        int $sortOrder,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $name,
            $type,
            $description,
            $isActive,
            $sortOrder,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): ServiceCategoryType
    {
        return $this->type;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function sortOrder(): int
    {
        return $this->sortOrder;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable;
    }
}
