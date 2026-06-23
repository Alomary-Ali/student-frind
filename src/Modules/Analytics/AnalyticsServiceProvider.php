<?php

declare(strict_types=1);

namespace Modules\Analytics;

use Illuminate\Support\ServiceProvider;

/**
 * AnalyticsServiceProvider
 *
 * Bootstraps the Analytics module.
 * Responsible for: Dashboards, KPIs, university reports, data insights
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        // TODO: Bind Repository interfaces to their Eloquent implementations
        // Example:
        // \->app->bind(
        //     \Modules\\Analytics\Domain\Contracts\SomeRepositoryInterface::class,
        //     \Modules\\Analytics\Infrastructure\Repositories\EloquentSomeRepository::class,
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
