<?php

declare(strict_types=1);

namespace Tests\Feature\AppProviders;

use App\Providers\AppServiceProvider;
use Tests\TestCase;

final class AppServiceProviderTest extends TestCase
{
    public function test_provider_is_registered_in_container(): void
    {
        $this->assertTrue(
            app()->providerIsLoaded(AppServiceProvider::class)
        );
    }

    public function test_provider_can_boot_without_errors(): void
    {
        $provider = new AppServiceProvider(app());
        $provider->boot();

        $this->assertTrue(true);
    }

    public function test_provider_can_register_without_errors(): void
    {
        $provider = new AppServiceProvider(app());
        $provider->register();

        $this->assertTrue(true);
    }
}
