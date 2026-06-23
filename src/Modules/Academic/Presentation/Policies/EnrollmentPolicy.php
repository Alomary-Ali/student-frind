<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Shared\Presentation\Policies\Policy;

final class EnrollmentPolicy extends Policy
{
    public function view(Authenticatable $user, string $enrollmentId): Response
    {
        // Students can view their own enrollments
        if ($this->isStudent($user)) {
            // TODO: Check if enrollment belongs to the student
            return $this->allow();
        }

        // Admins and advisors can view any enrollment
        if ($this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لعرض التسجيلات');
    }

    public function create(Authenticatable $user): Response
    {
        // Students can create their own enrollments
        if ($this->isStudent($user)) {
            return $this->allow();
        }

        // Admins and advisors can create enrollments
        if ($this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لإنشاء تسجيل');
    }

    public function delete(Authenticatable $user, string $enrollmentId): Response
    {
        // Students can delete their own enrollments (within limits)
        if ($this->isStudent($user)) {
            // TODO: Check if enrollment belongs to the student and within allowed timeframe
            return $this->allow();
        }

        // Admins and advisors can delete enrollments
        if ($this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لحذف التسجيل');
    }
}
