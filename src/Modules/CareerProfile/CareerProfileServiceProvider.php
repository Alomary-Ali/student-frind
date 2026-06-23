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
            \Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface::class,
            \Modules\CareerProfile\Infrastructure\Persistence\EloquentCareerProfileRepository::class,
        );

        $this->app->bind(
            \Modules\CareerProfile\Domain\Contracts\PortfolioItemRepositoryInterface::class,
            \Modules\CareerProfile\Infrastructure\Persistence\EloquentPortfolioItemRepository::class,
        );

        $this->app->bind(
            \Modules\CareerProfile\Domain\Contracts\ExperienceRepositoryInterface::class,
            \Modules\CareerProfile\Infrastructure\Persistence\EloquentExperienceRepository::class,
        );

        $this->app->bind(
            \Modules\CareerProfile\Domain\Contracts\ResumeRepositoryInterface::class,
            \Modules\CareerProfile\Infrastructure\Persistence\EloquentResumeRepository::class,
        );

        $this->app->bind(
            \Modules\CareerProfile\Domain\Contracts\CareerGoalRepositoryInterface::class,
            \Modules\CareerProfile\Infrastructure\Persistence\EloquentCareerGoalRepository::class,
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
