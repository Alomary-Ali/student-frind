<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AuthorizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'يجب تسجيل الدخول للوصول إلى هذه الصفحة',
                ],
            ], 401);
        }

        return $next($request);
    }
}
