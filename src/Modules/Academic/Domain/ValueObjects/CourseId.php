<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ValueObjects;

use Modules\Academic\Domain\Exceptions\InvalidCourseIdException;
use Ramsey\Uuid\Uuid;

final class CourseId
{
    private function __construct(
        private readonly string $value,
    ) {}

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        if (! Uuid::isValid($value)) {
            throw InvalidCourseIdException::invalidFormat($value);
        }

        return new self($value);
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
