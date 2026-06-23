<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

use Modules\Shared\Domain\Entities\Permission;
use Modules\Shared\Domain\ValueObjects\Permission as PermissionValue;
use Modules\Shared\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\RoleId;

interface PermissionRepositoryInterface
{
    public function findById(PermissionId $id): ?Permission;

    public function findByName(PermissionValue $name): ?Permission;

    /**
     * @return array<Permission>
     */
    public function findAll(): array;

    public function save(Permission $permission): void;

    public function delete(PermissionId $id): void;

    /**
     * @return array<Permission>
     */
    public function findByRoleId(RoleId $roleId): array;
}
