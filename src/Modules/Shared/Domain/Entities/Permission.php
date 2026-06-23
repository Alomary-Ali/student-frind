<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Entities;

use Modules\Shared\Domain\ValueObjects\Permission as PermissionValue;
use Modules\Shared\Domain\ValueObjects\PermissionId;

final readonly class Permission
{
    private function __construct(
        private PermissionId $id,
        private PermissionValue $name,
        private string $description,
    ) {}

    public static function create(
        PermissionValue $name,
        string $description,
    ): self {
        return new self(
            PermissionId::generate(),
            $name,
            $description,
        );
    }

    public static function reconstitute(
        PermissionId $id,
        PermissionValue $name,
        string $description,
    ): self {
        return new self($id, $name, $description);
    }

    public function id(): PermissionId
    {
        return $this->id;
    }

    public function name(): PermissionValue
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }
}
