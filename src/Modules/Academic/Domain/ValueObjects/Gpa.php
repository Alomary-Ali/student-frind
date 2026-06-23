<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ValueObjects;

use Modules\Academic\Domain\Exceptions\InvalidGpaException;

final class Gpa
{
    private function __construct(
        private readonly float $value,
    ) {}

    public static function of(float $value): self
    {
        if ($value < 0.0 || $value > 4.0) {
            throw InvalidGpaException::outOfRange($value);
        }

        return new self(round($value, 2));
    }

    public static function zero(): self
    {
        return new self(0.0);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
