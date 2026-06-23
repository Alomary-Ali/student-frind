<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Integrations;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final class LaravelEventDispatcher implements EventDispatcherInterface
{
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            event($event);
        }
    }
}
