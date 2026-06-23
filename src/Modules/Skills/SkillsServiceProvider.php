<?php

declare(strict_types=1);

namespace Modules\Skills;

use Illuminate\Support\ServiceProvider;

/**
 * SkillsServiceProvider
 *
 * Bootstraps the Skills module.
 * Responsible for: Skill profiles, competencies, certificates
 *
 * Register all bindings, routes, migrations, and listeners
 * specific to this module here.
 */
final class SkillsServiceProvider extends ServiceProvider
{
    /**
     * Register module bindings into the service container.
     */
    public function register(): void
    {
        $this->app->bind(
            Domain\Contracts\SkillProfileRepositoryInterface::class,
            Infrastructure\Persistence\EloquentSkillProfileRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\SkillRepositoryInterface::class,
            Infrastructure\Persistence\EloquentSkillRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\CertificationRepositoryInterface::class,
            Infrastructure\Persistence\EloquentCertificationRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\AchievementRepositoryInterface::class,
            Infrastructure\Persistence\EloquentAchievementRepository::class,
        );

        $this->app->bind(
            Domain\Contracts\LearningPathRepositoryInterface::class,
            Infrastructure\Persistence\EloquentLearningPathRepository::class,
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
