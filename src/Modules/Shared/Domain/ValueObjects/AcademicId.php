<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class AcademicId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public static function of(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Academic ID cannot be empty');
        }

        if (! preg_match('/^\d{8}$/', $value)) {
            throw new InvalidArgumentException('Academic ID must be 8 digits');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
