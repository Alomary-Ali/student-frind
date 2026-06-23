<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class SemesterPlanId
{
    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        if (empty($value)) {
            throw new InvalidArgumentException('SemesterPlanId cannot be empty');
        }

        return new self($value);
    }

    public static function generate(): self
    {
        return new self((string) \Illuminate\Support\Str::uuid());
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
