<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ValueObjects;

use Modules\Academic\Domain\Exceptions\InvalidCreditsException;

final class Credits
{
    private function __construct(
        private readonly int $value,
    ) {}

    public static function of(int $value): self
    {
        if ($value < 0 || $value > 30) {
            throw InvalidCreditsException::outOfRange($value);
        }

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(self $other): self
    {
        return self::of($this->value + $other->value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
