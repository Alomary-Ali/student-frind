<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        \Illuminate\Support\Facades\Log::info('RoleMiddleware check', [
            'path' => $request->path(),
            'user_id' => $user ? $user->id : 'guest',
            'user_email' => $user ? $user->email : 'guest',
            'user_role' => $user ? $user->role : 'none',
            'required_roles' => $roles,
        ]);

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Unauthenticated',
                    'errors' => null,
                    'meta' => null,
                ], 401);
            }
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles)) {
            \Illuminate\Support\Facades\Log::warning('RoleMiddleware rejected access', [
                'user_role' => $user->role,
                'required_roles' => $roles,
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Forbidden: insufficient permissions',
                    'errors' => null,
                    'meta' => null,
                ], 403);
            }
            return redirect()->route('home')->with('error', 'ليس لديك صلاحية الوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
