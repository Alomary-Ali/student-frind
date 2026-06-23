<?php

declare(strict_types=1);

namespace Modules\Shared;

use Illuminate\Support\ServiceProvider;

/**
 * SharedServiceProvider
 *
 * Bootstraps the Shared module.
 * Responsible for: Users, Authentication, Authorization, Notifications, Files, Audit, Settings
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class SharedServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        $this->app->bind(
            \Modules\Shared\Domain\Contracts\UserRepositoryInterface::class,
            \Modules\Shared\Infrastructure\Repositories\EloquentUserRepository::class
        );

        $this->app->bind(
            \Modules\Shared\Domain\Contracts\EventDispatcherInterface::class,
            \Modules\Shared\Infrastructure\Integrations\LaravelEventDispatcher::class
        );

        $this->app->bind(
            \Modules\Shared\Domain\Contracts\AuthServiceInterface::class,
            \Modules\Shared\Infrastructure\Auth\SanctumAuthService::class
        );

        // Register Authorization Repositories
        $this->app->bind(
            \Modules\Shared\Domain\Contracts\RoleRepositoryInterface::class,
            \Modules\Shared\Infrastructure\Repositories\EloquentRoleRepository::class
        );

        $this->app->bind(
            \Modules\Shared\Domain\Contracts\PermissionRepositoryInterface::class,
            \Modules\Shared\Infrastructure\Repositories\EloquentPermissionRepository::class
        );

        // Register Use Cases
        $this->app->bind(
            \Modules\Shared\Application\UseCases\AuthenticateUser::class
        );
    }

    /**
     * Bootstrap module services.
     * Load routes, migrations, event listeners, and policies.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/Infrastructure/Persistence/Migrations');
    }
}
