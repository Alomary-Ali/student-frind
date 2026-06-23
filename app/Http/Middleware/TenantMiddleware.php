<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TenantMiddleware — Multi-Tenancy Scope Isolation.
 *
 * Resolves the current tenant (institution) from the authenticated user's
 * institution_id and binds it to the container so all Repositories can
 * automatically scope queries to the correct tenant.
 *
 * Usage in routes:
 *   Route::middleware(['auth', 'tenant'])->group(...)
 *
 * @todo Phase 2: Add TenantService + automatic Eloquent global scope
 */
final class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        // Resolve institution_id from the authenticated user's student record
        $institutionId = \Illuminate\Support\Facades\DB::table('academic_students')
            ->where('user_id', $user->id)
            ->value('institution_id');

        if ($institutionId === null) {
            // User has no institution yet — allow but don't scope
            // In strict multi-tenant mode, you would abort(403) here
            app()->instance('tenant.id', null);
            return $next($request);
        }

        // Bind tenant context to the IoC container
        app()->instance('tenant.id', $institutionId);

        // Store in request for Controllers
        $request->attributes->set('institution_id', $institutionId);

        return $next($request);
    }
}
