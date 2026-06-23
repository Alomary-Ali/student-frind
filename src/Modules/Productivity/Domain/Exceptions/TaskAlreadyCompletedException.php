<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class TaskAlreadyCompletedException extends DomainException
{
    public static function forTask(string $taskId): self
    {
        return new self("Task {$taskId} is already completed");
    }
}
