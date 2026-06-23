<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use Ramsey\Uuid\Uuid;

final class KnowledgeCategory
{
    private function __construct(
        private readonly string $id,
        private string $name,
        private string $slug,
        private ?string $parentId,
        private ?string $description,
        private int $sortOrder,
    ) {}

    public static function create(
        string $name,
        string $slug,
        ?string $parentId = null,
        ?string $description = null,
        int $sortOrder = 0,
    ): self {
        return new self(
            Uuid::uuid4()->toString(),
            $name,
            $slug,
            $parentId,
            $description,
            $sortOrder,
        );
    }

    public static function reconstitute(
        string $id,
        string $name,
        string $slug,
        ?string $parentId,
        ?string $description,
        int $sortOrder,
    ): self {
        return new self(
            $id,
            $name,
            $slug,
            $parentId,
            $description,
            $sortOrder,
        );
    }

    public function update(string $name, string $slug, ?string $parentId, ?string $description, int $sortOrder): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->parentId = $parentId;
        $this->description = $description;
        $this->sortOrder = $sortOrder;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function parentId(): ?string
    {
        return $this->parentId;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function sortOrder(): int
    {
        return $this->sortOrder;
    }
}
