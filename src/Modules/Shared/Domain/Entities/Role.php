<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Entities;

use Modules\Shared\Domain\Enums\Role as RoleEnum;
use Modules\Shared\Domain\ValueObjects\Permission;
use Modules\Shared\Domain\ValueObjects\RoleId;

final readonly class Role
{
    /**
     * @param  array<Permission>  $permissions
     */
    private function __construct(
        private RoleId $id,
        private RoleEnum $name,
        private array $permissions,
    ) {}

    public static function create(RoleEnum $name): self
    {
        return new self(
            RoleId::generate(),
            $name,
            [],
        );
    }

    public static function reconstitute(
        RoleId $id,
        RoleEnum $name,
        array $permissions,
    ): self {
        return new self($id, $name, $permissions);
    }

    public function id(): RoleId
    {
        return $this->id;
    }

    public function name(): RoleEnum
    {
        return $this->name;
    }

    /**
     * @return array<Permission>
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    public function hasPermission(Permission $permission): bool
    {
        foreach ($this->permissions as $existingPermission) {
            if ($existingPermission->equals($permission)) {
                return true;
            }
        }

        return false;
    }

    public function addPermission(Permission $permission): self
    {
        if ($this->hasPermission($permission)) {
            return $this;
        }

        $newPermissions = [...$this->permissions, $permission];

        return new self(
            $this->id,
            $this->name,
            $newPermissions,
        );
    }

    public function removePermission(Permission $permission): self
    {
        $newPermissions = array_filter(
            $this->permissions,
            fn (Permission $p) => ! $p->equals($permission),
        );

        return new self(
            $this->id,
            $this->name,
            array_values($newPermissions),
        );
    }
}
