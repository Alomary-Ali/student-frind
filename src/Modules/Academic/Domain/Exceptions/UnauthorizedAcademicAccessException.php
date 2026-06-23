<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class UnauthorizedAcademicAccessException extends DomainException
{
    public static function forAction(string $action): self
    {
        return new self("Unauthorized to perform action: {$action}");
    }
}
