<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Exceptions;

use DomainException;

final class InvalidInterviewQuestionIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidInterviewQuestionIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidInterviewQuestionIdException: invalid format for value {$value}");
    }
}
