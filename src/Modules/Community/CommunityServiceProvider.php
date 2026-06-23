<?php

declare(strict_types=1);

namespace Modules\Community;

use Illuminate\Support\ServiceProvider;

/**
 * CommunityServiceProvider
 *
 * Bootstraps the Community module.
 * Responsible for: Forums, groups, events, mentorship, peer connections
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class CommunityServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        // TODO: Bind Repository interfaces to their Eloquent implementations
        // Example:
        // \->app->bind(
        //     \Modules\\Community\Domain\Contracts\SomeRepositoryInterface::class,
        //     \Modules\\Community\Infrastructure\Repositories\EloquentSomeRepository::class,
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
