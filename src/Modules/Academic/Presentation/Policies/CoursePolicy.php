<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Shared\Presentation\Policies\Policy;

final class CoursePolicy extends Policy
{
    public function view(Authenticatable $user, string $courseId): Response
    {
        // All authenticated users can view courses
        if ($this->isStudent($user) || $this->isFaculty($user) || $this->isAdmin($user) || $this->isAdvisor($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لعرض المقررات');
    }

    public function create(Authenticatable $user): Response
    {
        // Only admins and faculty can create courses
        if ($this->isAdmin($user) || $this->isFaculty($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لإنشاء المقررات');
    }

    public function update(Authenticatable $user, string $courseId): Response
    {
        // Only admins and faculty can update courses
        if ($this->isAdmin($user) || $this->isFaculty($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لتعديل المقررات');
    }

    public function delete(Authenticatable $user, string $courseId): Response
    {
        // Only admins can delete courses
        if ($this->isAdmin($user)) {
            return $this->allow();
        }

        return $this->deny('ليس لديك الصلاحية لحذف المقررات');
    }
}
