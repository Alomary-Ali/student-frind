<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Shared\Domain\Contracts\RoleRepositoryInterface;
use Modules\Shared\Domain\ValueObjects\Permission;
use Symfony\Component\HttpFoundation\Response;

final class PermissionMiddleware
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    /**
     * @param  array<string>  $permissions
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'يجب تسجيل الدخول للوصول إلى هذه الصفحة',
                ],
            ], 401);
        }

        $userRoles = $this->roleRepository->findByUserIds([$user->id]);

        $hasRequiredPermission = false;

        foreach ($userRoles as $role) {
            foreach ($permissions as $permission) {
                if ($role->hasPermission(Permission::of($permission))) {
                    $hasRequiredPermission = true;
                    break 2;
                }
            }
        }

        if (! $hasRequiredPermission) {
            return response()->json([
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'ليس لديك الصلاحية للوصول إلى هذه الصفحة',
                ],
            ], 403);
        }

        return $next($request);
    }
}
