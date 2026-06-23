<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Shared\Presentation\Policies\Policy;

final class StudentPolicy extends Policy
{
    public function view(Authenticatable $user, string $studentId): Response
    {
        // Students can only view their own data
        if ($this->isStudent($user)) {
            return $user->academic_id === $studentId
                ? $this->allow()
                : $this->deny('يمكنك فقط عرض بياناتك الخاصة');
        }

        // Admins and advisors can view any student data
        if ($this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لعرض بيانات الطلاب');
    }

    public function update(Authenticatable $user, string $studentId): Response
    {
        // Students cannot update their own academic data (only admins/advisors)
        if ($this->isStudent($user)) {
            return $this->deny('الطلاب لا يمكنهم تعديل بياناتهم الأكاديمية');
        }

        // Admins and advisors can update student data
        if ($this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لتعديل بيانات الطلاب');
    }

    public function delete(Authenticatable $user, string $studentId): Response
    {
        // Only admins can delete student data
        if ($this->isAdmin($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لحذف بيانات الطلاب');
    }

    public function create(Authenticatable $user): Response
    {
        // Only admins and advisors can create student profiles
        if ($this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لإنشاء بيانات الطلاب');
    }
}
