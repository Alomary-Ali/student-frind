<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Repositories;

use Modules\Shared\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Shared\Domain\Entities\Permission;
use Modules\Shared\Domain\ValueObjects\Permission as PermissionValue;
use Modules\Shared\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\RoleId;
use Modules\Shared\Infrastructure\Persistence\EloquentPermission;
use Modules\Shared\Infrastructure\Persistence\EloquentRole;

final class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    public function findById(PermissionId $id): ?Permission
    {
        $model = EloquentPermission::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findByName(PermissionValue $name): ?Permission
    {
        $model = EloquentPermission::where('name', $name->value())->first();

        if ($model === null) {
            return null;
        }

        return $this->toDomain($model);
    }

    /**
     * @return array<Permission>
     */
    public function findAll(): array
    {
        $models = EloquentPermission::all();

        return $models->map(fn (EloquentPermission $model) => $this->toDomain($model))->toArray();
    }

    public function save(Permission $permission): void
    {
        $model = EloquentPermission::find($permission->id()->value());

        if ($model === null) {
            $model = new EloquentPermission;
            $model->id = $permission->id()->value();
        }

        $model->name = $permission->name()->value();
        $model->description = $permission->description();
        $model->save();
    }

    public function delete(PermissionId $id): void
    {
        EloquentPermission::destroy($id->value());
    }

    /**
     * @return array<Permission>
     */
    public function findByRoleId(RoleId $roleId): array
    {
        $role = EloquentRole::with('permissions')->find($roleId->value());

        if ($role === null) {
            return [];
        }

        return $role->permissions
            ->map(fn (EloquentPermission $model) => $this->toDomain($model))
            ->toArray();
    }

    private function toDomain(EloquentPermission $model): Permission
    {
        return Permission::reconstitute(
            PermissionId::fromString($model->id),
            PermissionValue::of($model->name),
            $model->description ?? '',
        );
    }
}
