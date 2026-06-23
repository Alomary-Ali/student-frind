<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

interface EventDispatcherInterface
{
    /**
     * Dispatch multiple domain events.
     *
     * @param list<object> $events
     */
    public function dispatch(array $events): void;
}
