<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ModuleServiceProvider
 *
 * Bootstraps all SSP domain modules by registering their
 * individual service providers with the Laravel application container.
 *
 * Adding a new module:
 *   1. Create src/Modules/{ModuleName}/{ModuleName}ServiceProvider.php
 *   2. Add it to the $modules array below.
 */
final class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Registered module service providers.
     * Each module is responsible for its own bindings, routes, and migrations.
     *
     * @var array<class-string<ServiceProvider>>
     */
    private array $modules = [
        \Modules\Shared\SharedServiceProvider::class,
        \Modules\Academic\AcademicServiceProvider::class,
        \Modules\Productivity\ProductivityServiceProvider::class,
        \Modules\Guidance\GuidanceServiceProvider::class,
        \Modules\Skills\SkillsServiceProvider::class,
        \Modules\CareerProfile\CareerProfileServiceProvider::class,
        \Modules\Opportunities\OpportunitiesServiceProvider::class,
        \Modules\Community\CommunityServiceProvider::class,
        \Modules\Analytics\AnalyticsServiceProvider::class,
        \Modules\Administration\AdministrationServiceProvider::class,
        \Modules\UI\UIServiceProvider::class,
        \Modules\Career\CareerServiceProvider::class,
        \Modules\Notifications\NotificationsServiceProvider::class,
        \Modules\StudentServices\StudentServicesServiceProvider::class,
    ];

    /**
     * Register module service providers into the container.
     */
    public function register(): void
    {
        foreach ($this->modules as $moduleProvider) {
            $this->app->register($moduleProvider);
        }
    }

    /**
     * Bootstrap module services after all providers are registered.
     */
    public function boot(): void
    {
        //
    }
}
