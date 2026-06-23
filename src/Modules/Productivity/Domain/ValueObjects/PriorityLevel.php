<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidPriorityLevelException;

final readonly class PriorityLevel
{
    private const LOW = 'low';
    private const MEDIUM = 'medium';
    private const HIGH = 'high';
    private const URGENT = 'urgent';

    private function __construct(private string $value) {}

    public static function low(): self
    {
        return new self(self::LOW);
    }

    public static function medium(): self
    {
        return new self(self::MEDIUM);
    }

    public static function high(): self
    {
        return new self(self::HIGH);
    }

    public static function urgent(): self
    {
        return new self(self::URGENT);
    }

    public static function fromString(string $value): self
    {
        $validValues = [self::LOW, self::MEDIUM, self::HIGH, self::URGENT];

        if (! in_array(strtolower($value), $validValues, true)) {
            throw InvalidPriorityLevelException::invalidValue($value);
        }

        return new self(strtolower($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function isLow(): bool
    {
        return $this->value === self::LOW;
    }

    public function isMedium(): bool
    {
        return $this->value === self::MEDIUM;
    }

    public function isHigh(): bool
    {
        return $this->value === self::HIGH;
    }

    public function isUrgent(): bool
    {
        return $this->value === self::URGENT;
    }

    public function weight(): int
    {
        return match ($this->value) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
            self::URGENT => 4,
        };
    }
}
