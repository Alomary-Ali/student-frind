<?php

declare(strict_types=1);

namespace Modules\Administration;

use Illuminate\Support\ServiceProvider;

/**
 * AdministrationServiceProvider
 *
 * Bootstraps the Administration module.
 * Responsible for: System config, multi-tenancy, roles, permissions
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        // TODO: Bind Repository interfaces to their Eloquent implementations
        // Example:
        // \->app->bind(
        //     \Modules\\Administration\Domain\Contracts\SomeRepositoryInterface::class,
        //     \Modules\\Administration\Infrastructure\Repositories\EloquentSomeRepository::class,
        // );
    }

    /**
     * Bootstrap module services.
     * Load routes, migrations, event listeners, and policies.
     */
    public function boot(): void
    {
        // \->loadRoutesFrom(__DIR__ . '/Presentation/Routes/api.php');
        // \->loadMigrationsFrom(__DIR__ . '/Infrastructure/Persistence/Migrations');
    }
}
