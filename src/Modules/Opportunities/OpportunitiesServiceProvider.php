<?php

declare(strict_types=1);

namespace Modules\Opportunities;

use Illuminate\Support\ServiceProvider;

/**
 * OpportunitiesServiceProvider
 *
 * Bootstraps the Opportunities module.
 * Responsible for: Jobs, internships, scholarships, competitions
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class OpportunitiesServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        // TODO: Bind Repository interfaces to their Eloquent implementations
        // Example:
        // \->app->bind(
        //     \Modules\\Opportunities\Domain\Contracts\SomeRepositoryInterface::class,
        //     \Modules\\Opportunities\Infrastructure\Repositories\EloquentSomeRepository::class,
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
