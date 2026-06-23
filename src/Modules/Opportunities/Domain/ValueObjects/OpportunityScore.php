<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\ValueObjects;

use InvalidArgumentException;

final class OpportunityScore
{
    private function __construct(
        private readonly float $value,
    ) {}

    public static function fromFloat(float $value): self
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException("OpportunityScore must be between 0 and 100, got {$value}");
        }

        return new self(round($value, 2));
    }

    public function value(): float
    {
        return $this->value;
    }

    public function percentage(): string
    {
        return number_format($this->value, 1) . '%';
    }

    public function isHigh(): bool
    {
        return $this->value >= 70;
    }

    public function isMedium(): bool
    {
        return $this->value >= 40 && $this->value < 70;
    }

    public function isLow(): bool
    {
        return $this->value < 40;
    }
}
