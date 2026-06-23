<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Domain\Entities\Permission;
use Modules\Shared\Domain\Entities\Role;
use Modules\Shared\Domain\Enums\Role as RoleEnum;
use Modules\Shared\Domain\ValueObjects\Permission as PermissionValue;
use Modules\Shared\Domain\Contracts\RoleRepositoryInterface;
use Modules\Shared\Domain\Contracts\PermissionRepositoryInterface;

final class AuthorizationSeeder extends Seeder
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
    ) {}

    public function run(): void
    {
        $this->createPermissions();
        $this->createRoles();
        $this->assignPermissionsToRoles();
    }

    private function createPermissions(): void
    {
        $permissions = [
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
        ];

        foreach ($permissions as $name => $description) {
            $existing = $this->permissionRepository->findByName(PermissionValue::of($name));

            if ($existing === null) {
                $permission = Permission::create(
                    PermissionValue::of($name),
                    $description,
                );

                $this->permissionRepository->save($permission);
            }
        }
    }

    private function createRoles(): void
    {
        $roles = [
            RoleEnum::SUPER_ADMIN,
            RoleEnum::ADMIN,
            RoleEnum::ADVISOR,
            RoleEnum::STUDENT,
            RoleEnum::FACULTY,
        ];

        foreach ($roles as $roleEnum) {
            $existing = $this->roleRepository->findByName($roleEnum);

            if ($existing === null) {
                $role = Role::create($roleEnum);
                $this->roleRepository->save($role);
            }
        }
    }

    private function assignPermissionsToRoles(): void
    {
        $superAdmin = $this->roleRepository->findByName(RoleEnum::SUPER_ADMIN);
        $admin = $this->roleRepository->findByName(RoleEnum::ADMIN);
        $advisor = $this->roleRepository->findByName(RoleEnum::ADVISOR);
        $student = $this->roleRepository->findByName(RoleEnum::STUDENT);
        $faculty = $this->roleRepository->findByName(RoleEnum::FACULTY);

        if ($superAdmin === null || $admin === null || $advisor === null || $student === null || $faculty === null) {
            return;
        }

        // Super Admin: All permissions
        $allPermissions = [
            'students.view', 'students.create', 'students.update', 'students.delete',
            'enrollments.view', 'enrollments.create', 'enrollments.delete',
            'grades.view', 'grades.create', 'grades.update',
            'reports.view', 'settings.manage',
        ];

        foreach ($allPermissions as $permissionName) {
            $superAdmin = $superAdmin->addPermission(PermissionValue::of($permissionName));
        }

        $this->roleRepository->save($superAdmin);

        // Admin: All except settings.manage
        $adminPermissions = [
            'students.view', 'students.create', 'students.update', 'students.delete',
            'enrollments.view', 'enrollments.create', 'enrollments.delete',
            'grades.view', 'grades.create', 'grades.update',
            'reports.view',
        ];

        foreach ($adminPermissions as $permissionName) {
            $admin = $admin->addPermission(PermissionValue::of($permissionName));
        }

        $this->roleRepository->save($admin);

        // Advisor: Limited permissions
        $advisorPermissions = [
            'students.view', 'students.create',
            'enrollments.view', 'enrollments.create',
            'grades.view', 'grades.create',
        ];

        foreach ($advisorPermissions as $permissionName) {
            $advisor = $advisor->addPermission(PermissionValue::of($permissionName));
        }

        $this->roleRepository->save($advisor);

        // Student: Very limited permissions
        $studentPermissions = [
            'enrollments.view', 'enrollments.create',
        ];

        foreach ($studentPermissions as $permissionName) {
            $student = $student->addPermission(PermissionValue::of($permissionName));
        }

        $this->roleRepository->save($student);

        // Faculty: Course-related permissions
        $facultyPermissions = [
            'enrollments.view',
            'grades.view', 'grades.create', 'grades.update',
        ];

        foreach ($facultyPermissions as $permissionName) {
            $faculty = $faculty->addPermission(PermissionValue::of($permissionName));
        }

        $this->roleRepository->save($faculty);
    }
}
