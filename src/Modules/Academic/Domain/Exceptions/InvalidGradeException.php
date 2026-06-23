<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidGradeException extends DomainException
{
    public static function pointsMismatch(string $letter, float $points): self
    {
        return new self("Grade points {$points} do not match letter grade {$letter}");
    }
}
