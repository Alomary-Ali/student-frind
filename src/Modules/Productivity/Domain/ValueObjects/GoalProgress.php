<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidGoalProgressException;

final readonly class GoalProgress
{
    private function __construct(private float $value) {}

    public static function zero(): self
    {
        return new self(0.0);
    }

    public static function complete(): self
    {
        return new self(100.0);
    }

    public static function of(float $value): self
    {
        if ($value < 0.0 || $value > 100.0) {
            throw InvalidGoalProgressException::outOfRange($value);
        }

        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function isComplete(): bool
    {
        return $this->value >= 100.0;
    }

    public function isStarted(): bool
    {
        return $this->value > 0.0;
    }

    public function add(float $amount): self
    {
        $newValue = $this->value + $amount;

        return self::of(min($newValue, 100.0));
    }
}
