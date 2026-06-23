<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class Policy
{
    protected function hasPermission(Authenticatable $user, string $permission): bool
    {
        // TODO: Implement permission check using role repository
        // For now, return false
        return false;
    }

    protected function hasRole(Authenticatable $user, string $role): bool
    {
        // TODO: Implement role check using role repository
        // For now, return false
        return false;
    }

    protected function isSuperAdmin(Authenticatable $user): bool
    {
        return $this->hasRole($user, 'super_admin');
    }

    protected function isAdmin(Authenticatable $user): bool
    {
        return $this->hasRole($user, 'admin');
    }

    protected function isAdvisor(Authenticatable $user): bool
    {
        return $this->hasRole($user, 'advisor');
    }

    protected function isStudent(Authenticatable $user): bool
    {
        return $this->hasRole($user, 'student');
    }

    protected function isFaculty(Authenticatable $user): bool
    {
        return $this->hasRole($user, 'faculty');
    }

    protected function deny(string $message = 'ليس لديك الصلاحية للقيام بهذا الإجراء'): Response
    {
        return Response::deny($message);
    }

    protected function allow(): Response
    {
        return Response::allow();
    }
}
