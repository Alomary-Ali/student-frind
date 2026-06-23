<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Exceptions;

use DomainException;

final class InvalidServiceStatusTransitionException extends DomainException
{
    public static function transitionNotAllowed(string $from, string $to): self
    {
        return new self("Invalid service status transition from {$from} to {$to}");
    }
}
