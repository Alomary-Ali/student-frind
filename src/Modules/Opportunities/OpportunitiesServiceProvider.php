<?php

declare(strict_types=1);

namespace Modules\Opportunities;

use Illuminate\Support\ServiceProvider;
use Modules\Opportunities\Domain\Contracts\ApplicationRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\RecommendationRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;
use Modules\Opportunities\Infrastructure\Persistence\EloquentApplicationRepository;
use Modules\Opportunities\Infrastructure\Persistence\EloquentOpportunityRepository;
use Modules\Opportunities\Infrastructure\Persistence\EloquentRecommendationRepository;
use Modules\Opportunities\Infrastructure\Persistence\EloquentSavedOpportunityRepository;

final class OpportunitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            OpportunityRepositoryInterface::class,
            EloquentOpportunityRepository::class,
        );

        $this->app->bind(
            ApplicationRepositoryInterface::class,
            EloquentApplicationRepository::class,
        );

        $this->app->bind(
            SavedOpportunityRepositoryInterface::class,
            EloquentSavedOpportunityRepository::class,
        );

        $this->app->bind(
            RecommendationRepositoryInterface::class,
            EloquentRecommendationRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Http/routes.php');
    }
}
