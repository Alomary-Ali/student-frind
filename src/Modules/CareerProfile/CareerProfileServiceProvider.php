<?php

declare(strict_types=1);

namespace Modules\CareerProfile;

use Illuminate\Support\ServiceProvider;

/**
 * CareerProfileServiceProvider
 *
 * Bootstraps the CareerProfile module.
 * Responsible for: Portfolio, CV builder, personal brand identity
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class CareerProfileServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        $this->app->bind(
            Domain\Contracts\CareerProfileRepositoryInterface::class,
            Infrastructure\Persistence\EloquentCareerProfileRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\PortfolioItemRepositoryInterface::class,
            Infrastructure\Persistence\EloquentPortfolioItemRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\ExperienceRepositoryInterface::class,
            Infrastructure\Persistence\EloquentExperienceRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\ResumeRepositoryInterface::class,
            Infrastructure\Persistence\EloquentResumeRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\CareerGoalRepositoryInterface::class,
            Infrastructure\Persistence\EloquentCareerGoalRepository::class,
        );
    }

    /**
     * Bootstrap module services.
     * Load routes, migrations, event listeners, and policies.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Http/routes.php');
    }
}
