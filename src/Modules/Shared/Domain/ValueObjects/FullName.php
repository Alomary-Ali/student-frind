<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

use Modules\Shared\Domain\Exceptions\InvalidFullNameException;

final class FullName
{
    private function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
    ) {}

    public static function of(string $firstName, string $lastName): self
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if (strlen($firstName) < 2 || strlen($firstName) > 50) {
            throw InvalidFullNameException::invalidFirstName($firstName);
        }

        if (strlen($lastName) < 2 || strlen($lastName) > 50) {
            throw InvalidFullNameException::invalidLastName($lastName);
        }

        return new self($firstName, $lastName);
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function full(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function equals(self $other): bool
    {
        return $this->firstName === $other->firstName
            && $this->lastName === $other->lastName;
    }

    public function __toString(): string
    {
        return $this->full();
    }
}
