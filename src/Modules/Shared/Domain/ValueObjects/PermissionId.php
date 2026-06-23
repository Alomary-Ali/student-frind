<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

use Illuminate\Support\Str;

final readonly class PermissionId
{
    private function __construct(
        private string $value,
    ) {
        $this->validate();
    }

    public static function generate(): self
    {
        return new self(Str::uuid()->toString());
    }

    public static function fromString(string $value): self
    {
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

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new \InvalidArgumentException('Permission ID cannot be empty');
        }

        if (!Str::isUuid($this->value)) {
            throw new \InvalidArgumentException('Permission ID must be a valid UUID');
        }
    }
}
