<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Shared\Domain\Contracts\RoleRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class RoleMiddleware
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    /**
     * @param  array<string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
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

        $hasRequiredRole = false;

        foreach ($userRoles as $role) {
            if (in_array($role->name()->value, $roles, true)) {
                $hasRequiredRole = true;
                break;
            }
        }

        if (! $hasRequiredRole) {
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
