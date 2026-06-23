<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidProjectIdException;

final readonly class ProjectId
{
    private function __construct(private string $value)
    {
        $this->validate($value);
    }

    public static function generate(): self
    {
        return new self((string) \Illuminate\Support\Str::uuid());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        if (! \Ramsey\Uuid\Uuid::isValid($value)) {
            throw new InvalidProjectIdException($value);
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
