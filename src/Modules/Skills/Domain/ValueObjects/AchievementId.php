<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\ValueObjects;

use Modules\Skills\Domain\Exceptions\InvalidAchievementIdException;
use Ramsey\Uuid\Uuid;

final class AchievementId
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
            throw InvalidAchievementIdException::invalidFormat($value);
        }

        return new self($value);
    }

    public static function of(string $value): self
    {
        return self::fromString($value);
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
