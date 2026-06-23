<?php

declare(strict_types=1);

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TenantMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Modules\Shared\Infrastructure\Middleware\AuthorizationMiddleware;
use Modules\Shared\Infrastructure\Middleware\PermissionMiddleware;
use Modules\Shared\Infrastructure\Middleware\SecurityHeadersMiddleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register named middleware aliases
        $middleware->alias([
            'tenant' => TenantMiddleware::class,
            'role' => RoleMiddleware::class,
            'auth.check' => AuthorizationMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'security.headers' => SecurityHeadersMiddleware::class,
        ]);

        // Apply security headers to all routes
        $middleware->append(SecurityHeadersMiddleware::class);

        // Redirect unauthenticated API requests to JSON response (not redirect)
        $middleware->redirectGuestsTo(fn (Request $request) => $request->expectsJson()
                ? null
                : route('login'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Return JSON errors for API routes
        $exceptions->respond(function (Response $response, Throwable $e, Request $request) {
            if ($request->is('api/*') && ! $response->isSuccessful()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => $e->getMessage(),
                    'errors' => null,
                    'meta' => null,
                ], $response->getStatusCode());
            }

            return $response;
        });
    })->create();
