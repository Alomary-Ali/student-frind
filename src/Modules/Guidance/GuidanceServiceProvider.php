<?php

declare(strict_types=1);

namespace Modules\Guidance;

use Illuminate\Support\ServiceProvider;

/**
 * GuidanceServiceProvider
 *
 * Bootstraps the Guidance module.
 * Responsible for: Advising sessions, early alerts, AI recommendations
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class GuidanceServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        // TODO: Bind Repository interfaces to their Eloquent implementations
        // Example:
        // \->app->bind(
        //     \Modules\\Guidance\Domain\Contracts\SomeRepositoryInterface::class,
        //     \Modules\\Guidance\Infrastructure\Repositories\EloquentSomeRepository::class,
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
