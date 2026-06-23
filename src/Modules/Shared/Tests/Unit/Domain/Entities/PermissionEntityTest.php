<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Domain\Entities;

use Modules\Shared\Domain\Entities\Permission;
use Modules\Shared\Domain\ValueObjects\Permission as PermissionValue;
use Modules\Shared\Domain\ValueObjects\PermissionId;
use PHPUnit\Framework\TestCase;

final class PermissionEntityTest extends TestCase
{
    public function test_permission_can_be_created(): void
    {
        $name = PermissionValue::of('students.view');
        $permission = Permission::create($name, 'View students');

        $this->assertNotNull($permission->id());
        $this->assertSame($name->value(), $permission->name()->value());
        $this->assertSame('View students', $permission->description());
    }

    public function test_permission_can_be_reconstituted(): void
    {
        $id = PermissionId::generate();
        $name = PermissionValue::of('students.create');
        $permission = Permission::reconstitute($id, $name, 'Create students');

        $this->assertSame($id->value(), $permission->id()->value());
        $this->assertSame('students.create', $permission->name()->value());
        $this->assertSame('Create students', $permission->description());
    }

    public function test_permission_uses_static_factory_methods(): void
    {
        $perm = Permission::create(PermissionValue::studentsView(), 'View student list');
        $this->assertSame('students.view', $perm->name()->value());
    }
}
