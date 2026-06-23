<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidGoalIdException;

final readonly class GoalId
{
    private function __construct(private string $value) {}

    public static function generate(): self
    {
        return new self(self::uuid4());
    }

    public static function fromString(string $value): self
    {
        if (! self::isValid($value)) {
            throw InvalidGoalIdException::invalidFormat($value);
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

    private static function isValid(string $value): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value) === 1;
    }

    private static function uuid4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
