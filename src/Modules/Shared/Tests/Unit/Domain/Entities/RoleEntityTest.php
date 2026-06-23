<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Domain\Entities;

use Modules\Shared\Domain\Entities\Role;
use Modules\Shared\Domain\Enums\Role as RoleEnum;
use Modules\Shared\Domain\ValueObjects\Permission;
use PHPUnit\Framework\TestCase;

final class RoleEntityTest extends TestCase
{
    public function test_role_can_be_created(): void
    {
        $role = Role::create(RoleEnum::ADMIN);

        $this->assertNotNull($role->id());
        $this->assertSame(RoleEnum::ADMIN, $role->name());
        $this->assertEmpty($role->permissions());
    }

    public function test_role_can_be_reconstituted(): void
    {
        $id = \Modules\Shared\Domain\ValueObjects\RoleId::generate();
        $perm = Permission::of('students.view');
        $role = Role::reconstitute($id, RoleEnum::STUDENT, [$perm]);

        $this->assertSame($id->value(), $role->id()->value());
        $this->assertSame(RoleEnum::STUDENT, $role->name());
        $this->assertCount(1, $role->permissions());
    }

    public function test_add_permission_adds_new_permission(): void
    {
        $role = Role::create(RoleEnum::ADVISOR);
        $perm = Permission::of('students.view');

        $newRole = $role->addPermission($perm);

        $this->assertCount(1, $newRole->permissions());
    }

    public function test_add_permission_does_not_duplicate(): void
    {
        $role = Role::create(RoleEnum::ADVISOR);
        $perm = Permission::of('students.view');

        $role = $role->addPermission($perm);
        $role = $role->addPermission($perm);

        $this->assertCount(1, $role->permissions());
    }

    public function test_remove_permission_removes_existing_permission(): void
    {
        $role = Role::create(RoleEnum::ADVISOR);
        $perm = Permission::of('students.view');
        $role = $role->addPermission($perm);

        $newRole = $role->removePermission($perm);

        $this->assertCount(0, $newRole->permissions());
    }

    public function test_remove_permission_does_nothing_if_not_present(): void
    {
        $role = Role::create(RoleEnum::ADVISOR);
        $perm = Permission::of('students.view');

        $newRole = $role->removePermission($perm);

        $this->assertCount(0, $newRole->permissions());
    }

    public function test_has_permission_returns_true_when_permission_exists(): void
    {
        $role = Role::create(RoleEnum::ADMIN);
        $perm = Permission::of('students.view');
        $role = $role->addPermission($perm);

        $this->assertTrue($role->hasPermission($perm));
    }

    public function test_has_permission_returns_false_when_permission_does_not_exist(): void
    {
        $role = Role::create(RoleEnum::ADMIN);
        $perm = Permission::of('students.view');

        $this->assertFalse($role->hasPermission($perm));
    }

    public function test_create_returns_immutable_instance(): void
    {
        $role = Role::create(RoleEnum::ADMIN);
        $perm = Permission::of('students.view');

        $newRole = $role->addPermission($perm);

        $this->assertNotSame($role, $newRole);
        $this->assertCount(0, $role->permissions());
        $this->assertCount(1, $newRole->permissions());
    }
}
