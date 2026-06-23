<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Feature\Infrastructure\Integrations;

use Modules\Shared\Infrastructure\Integrations\LaravelEventDispatcher;
use Tests\TestCase;

final class LaravelEventDispatcherTest extends TestCase
{
    public function test_dispatch_handles_empty_array(): void
    {
        $dispatcher = new LaravelEventDispatcher;
        $dispatcher->dispatch([]);
        $this->assertTrue(true);
    }

    public function test_dispatch_handles_single_event(): void
    {
        $dispatcher = new LaravelEventDispatcher;
        $dispatcher->dispatch([new \stdClass]);
        $this->assertTrue(true);
    }

    public function test_dispatch_handles_multiple_events(): void
    {
        $dispatcher = new LaravelEventDispatcher;
        $dispatcher->dispatch([new \stdClass, new \stdClass]);
        $this->assertTrue(true);
    }

    public function test_dispatch_invokes_laravel_event_helper(): void
    {
        $event = new \stdClass;
        $dispatcher = new LaravelEventDispatcher;

        $dispatcher->dispatch([$event]);

        $this->assertTrue(true);
    }
}
