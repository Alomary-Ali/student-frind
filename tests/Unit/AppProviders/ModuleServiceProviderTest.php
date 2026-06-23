<?php

declare(strict_types=1);

namespace Tests\Unit\AppProviders;

use App\Providers\ModuleServiceProvider;
use Tests\TestCase;

final class ModuleServiceProviderTest extends TestCase
{
    private const EXPECTED_MODULES = [
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
    ];

    public function test_provider_is_registered_in_container(): void
    {
        $this->assertTrue(
            app()->providerIsLoaded(ModuleServiceProvider::class),
        );
    }

    public function test_all_expected_module_providers_exist(): void
    {
        foreach (self::EXPECTED_MODULES as $providerClass) {
            $this->assertTrue(
                class_exists($providerClass),
                "Expected module provider $providerClass does not exist",
            );
        }
    }

    public function test_private_modules_array_contains_expected_providers(): void
    {
        $provider = new ModuleServiceProvider(app());
        $reflection = new \ReflectionClass($provider);
        $property = $reflection->getProperty('modules');
        $modules = $property->getValue($provider);

        $this->assertCount(count(self::EXPECTED_MODULES), $modules);

        foreach (self::EXPECTED_MODULES as $expected) {
            $this->assertContains($expected, $modules);
        }
    }

    public function test_register_method_runs_without_errors(): void
    {
        $provider = new ModuleServiceProvider(app());
        $provider->register();

        $this->assertTrue(true);
    }
}
