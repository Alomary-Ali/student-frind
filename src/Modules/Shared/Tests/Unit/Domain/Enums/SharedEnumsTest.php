<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Domain\Enums;

use Modules\Shared\Domain\Enums\Role;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Enums\UserStatus;
use PHPUnit\Framework\TestCase;

final class SharedEnumsTest extends TestCase
{
    public function test_user_role_has_all_cases(): void
    {
        $cases = UserRole::cases();
        $values = array_map(fn (UserRole $c) => $c->value, $cases);

        $this->assertContains('student', $values);
        $this->assertContains('advisor', $values);
        $this->assertContains('career_counselor', $values);
        $this->assertContains('admin', $values);
        $this->assertContains('mentor', $values);
        $this->assertContains('employer', $values);
    }

    public function test_user_role_labels(): void
    {
        $this->assertSame('Student', UserRole::Student->label());
        $this->assertSame('Academic Advisor', UserRole::Advisor->label());
        $this->assertSame('Career Counselor', UserRole::CareerCounselor->label());
        $this->assertSame('Administrator', UserRole::Admin->label());
        $this->assertSame('Mentor', UserRole::Mentor->label());
        $this->assertSame('Employer', UserRole::Employer->label());
    }

    public function test_user_role_is_academic_staff(): void
    {
        $this->assertTrue(UserRole::Advisor->isAcademicStaff());
        $this->assertTrue(UserRole::CareerCounselor->isAcademicStaff());
        $this->assertTrue(UserRole::Admin->isAcademicStaff());
        $this->assertFalse(UserRole::Student->isAcademicStaff());
        $this->assertFalse(UserRole::Mentor->isAcademicStaff());
    }

    public function test_user_role_is_external(): void
    {
        $this->assertTrue(UserRole::Mentor->isExternal());
        $this->assertTrue(UserRole::Employer->isExternal());
        $this->assertFalse(UserRole::Student->isExternal());
        $this->assertFalse(UserRole::Advisor->isExternal());
    }

    public function test_user_role_from_string(): void
    {
        $this->assertSame(UserRole::Student, UserRole::from('student'));
        $this->assertSame(UserRole::Admin, UserRole::from('admin'));
    }

    public function test_user_status_has_all_cases(): void
    {
        $cases = UserStatus::cases();
        $values = array_map(fn (UserStatus $c) => $c->value, $cases);

        $this->assertContains('active', $values);
        $this->assertContains('inactive', $values);
        $this->assertContains('suspended', $values);
        $this->assertContains('pending_verification', $values);
    }

    public function test_user_status_labels(): void
    {
        $this->assertSame('Active', UserStatus::Active->label());
        $this->assertSame('Inactive', UserStatus::Inactive->label());
        $this->assertSame('Suspended', UserStatus::Suspended->label());
        $this->assertSame('Pending Email Verification', UserStatus::PendingVerification->label());
    }

    public function test_user_status_is_allowed_to_login(): void
    {
        $this->assertTrue(UserStatus::Active->isAllowedToLogin());
        $this->assertTrue(UserStatus::PendingVerification->isAllowedToLogin());
        $this->assertFalse(UserStatus::Suspended->isAllowedToLogin());
        $this->assertFalse(UserStatus::Inactive->isAllowedToLogin());
    }

    public function test_role_enum_has_all_cases(): void
    {
        $cases = Role::cases();
        $values = array_map(fn (Role $c) => $c->value, $cases);

        $this->assertContains('super_admin', $values);
        $this->assertContains('admin', $values);
        $this->assertContains('advisor', $values);
        $this->assertContains('student', $values);
        $this->assertContains('faculty', $values);
    }

    public function test_role_labels(): void
    {
        $this->assertSame('مدير النظام', Role::SUPER_ADMIN->label());
        $this->assertSame('مدير الجامعة', Role::ADMIN->label());
        $this->assertSame('المرشد الأكاديمي', Role::ADVISOR->label());
        $this->assertSame('الطالب', Role::STUDENT->label());
        $this->assertSame('عضو هيئة التدريس', Role::FACULTY->label());
    }

    public function test_role_capabilities(): void
    {
        $this->assertTrue(Role::SUPER_ADMIN->canManageSystem());
        $this->assertFalse(Role::ADMIN->canManageSystem());

        $this->assertTrue(Role::SUPER_ADMIN->canManageUniversity());
        $this->assertTrue(Role::ADMIN->canManageUniversity());
        $this->assertFalse(Role::STUDENT->canManageUniversity());

        $this->assertTrue(Role::SUPER_ADMIN->canAdviseStudents());
        $this->assertTrue(Role::ADVISOR->canAdviseStudents());
        $this->assertFalse(Role::STUDENT->canAdviseStudents());

        $this->assertTrue(Role::SUPER_ADMIN->canManageCourses());
        $this->assertTrue(Role::FACULTY->canManageCourses());
        $this->assertFalse(Role::STUDENT->canManageCourses());
    }
}
