<?php

declare(strict_types=1);

namespace Modules\Career\Domain\ValueObjects;

use Modules\Career\Domain\Exceptions\InvalidPortfolioSlugException;

final class PortfolioSlug
{
    private function __construct(
        private readonly string $value,
    ) {}

    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        if ($trimmed === '' || ! preg_match('/^[a-z0-9][a-z0-9-]{1,98}[a-z0-9]$/', $trimmed)) {
            throw InvalidPortfolioSlugException::invalidFormat($value);
        }

        return new self($trimmed);
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
