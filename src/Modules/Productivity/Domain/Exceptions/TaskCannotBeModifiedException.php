<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class TaskCannotBeModifiedException extends DomainException
{
    public static function taskCompleted(string $taskId): self
    {
        return new self("Task {$taskId} is completed and cannot be modified");
    }
}
