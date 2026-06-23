<?php

declare(strict_types=1);

namespace Modules\Career;

use Illuminate\Support\ServiceProvider;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Contracts\Gateways\CareerProfileGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\OpportunitiesGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\Contracts\PublicPortfolioRepositoryInterface;
use Modules\Career\Infrastructure\Gateways\CareerProfileGateway;
use Modules\Career\Infrastructure\Gateways\OpportunitiesGateway;
use Modules\Career\Infrastructure\Gateways\SkillsGateway;
use Modules\Career\Infrastructure\Persistence\EloquentCareerPathRepository;
use Modules\Career\Infrastructure\Persistence\EloquentInterviewRepository;
use Modules\Career\Infrastructure\Persistence\EloquentPublicPortfolioRepository;

final class CareerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InterviewRepositoryInterface::class, EloquentInterviewRepository::class);
        $this->app->bind(CareerPathRepositoryInterface::class, EloquentCareerPathRepository::class);
        $this->app->bind(PublicPortfolioRepositoryInterface::class, EloquentPublicPortfolioRepository::class);

        $this->app->bind(CareerProfileGatewayInterface::class, CareerProfileGateway::class);
        $this->app->bind(SkillsGatewayInterface::class, SkillsGateway::class);
        $this->app->bind(OpportunitiesGatewayInterface::class, OpportunitiesGateway::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Http/routes.php');
    }
}
