<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

use Modules\Shared\Domain\Exceptions\InvalidEmailAddressException;

final class EmailAddress
{
    private function __construct(
        private readonly string $value,
    ) {}

    public static function fromString(string $email): self
    {
        $normalized = strtolower(trim($email));

        if (! filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailAddressException::invalidFormat($email);
        }

        return new self($normalized);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
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
