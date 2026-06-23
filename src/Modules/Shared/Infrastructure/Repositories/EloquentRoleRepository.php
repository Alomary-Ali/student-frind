<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Repositories;

use Modules\Shared\Domain\Contracts\RoleRepositoryInterface;
use Modules\Shared\Domain\Entities\Role;
use Modules\Shared\Domain\Enums\Role as RoleEnum;
use Modules\Shared\Domain\ValueObjects\Permission;
use Modules\Shared\Domain\ValueObjects\RoleId;
use Modules\Shared\Infrastructure\Persistence\EloquentPermission;
use Modules\Shared\Infrastructure\Persistence\EloquentRole;

final class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function findById(RoleId $id): ?Role
    {
        $model = EloquentRole::with('permissions')->find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findByName(RoleEnum $name): ?Role
    {
        $model = EloquentRole::with('permissions')->where('name', $name->value)->first();

        if ($model === null) {
            return null;
        }

        return $this->toDomain($model);
    }

    /**
     * @return array<Role>
     */
    public function findAll(): array
    {
        $models = EloquentRole::with('permissions')->get();

        return $models->map(fn (EloquentRole $model) => $this->toDomain($model))->toArray();
    }

    public function save(Role $role): void
    {
        $model = EloquentRole::find($role->id()->value());

        if ($model === null) {
            $model = new EloquentRole;
            $model->id = $role->id()->value();
        }

        $model->name = $role->name()->value;
        $model->label = $role->name()->label();
        $model->save();

        // Sync permissions
        $permissionIds = array_map(
            fn (Permission $permission) => $this->findOrCreatePermission($permission),
            $role->permissions(),
        );

        $model->permissions()->sync($permissionIds);
    }

    public function delete(RoleId $id): void
    {
        EloquentRole::destroy($id->value());
    }

    /**
     * @param  array<string>  $userIds
     * @return array<Role>
     */
    public function findByUserIds(array $userIds): array
    {
        $models = EloquentRole::with('permissions')
            ->whereHas('users', fn ($query) => $query->whereIn('id', $userIds))
            ->get();

        return $models->map(fn (EloquentRole $model) => $this->toDomain($model))->toArray();
    }

    private function toDomain(EloquentRole $model): Role
    {
        $permissions = array_map(
            fn (EloquentPermission $permission) => Permission::of($permission->name),
            $model->permissions->all(),
        );

        return Role::reconstitute(
            RoleId::fromString($model->id),
            RoleEnum::from($model->name),
            $permissions,
        );
    }

    private function findOrCreatePermission(Permission $permission): string
    {
        $model = EloquentPermission::where('name', $permission->value())->first();

        if ($model === null) {
            $model = new EloquentPermission;
            $model->id = \Illuminate\Support\Str::uuid()->toString();
            $model->name = $permission->value();
            $model->description = $this->getPermissionDescription($permission->value());
            $model->save();
        }

        return $model->id;
    }

    private function getPermissionDescription(string $permission): string
    {
        return match ($permission) {
            'students.view' => 'عرض بيانات الطلاب',
            'students.create' => 'إنشاء طالب',
            'students.update' => 'تعديل بيانات الطالب',
            'students.delete' => 'حذف طالب',
            'enrollments.view' => 'عرض التسجيلات',
            'enrollments.create' => 'إنشاء تسجيل',
            'enrollments.delete' => 'حذف تسجيل',
            'grades.view' => 'عرض الدرجات',
            'grades.create' => 'إضافة درجات',
            'grades.update' => 'تعديل درجات',
            'reports.view' => 'عرض التقارير',
            'settings.manage' => 'إدارة الإعدادات',
            default => '',
        };
    }
}
