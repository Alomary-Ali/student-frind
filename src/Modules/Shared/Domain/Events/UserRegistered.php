<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Events;

use DateTimeImmutable;

final readonly class UserRegistered
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $fullName,
        public string $role,
        public DateTimeImmutable $occurredAt,
    ) {}
}
