<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Events;

use DateTimeImmutable;

final readonly class UserEmailVerified
{
    public function __construct(
        public string $userId,
        public string $email,
        public DateTimeImmutable $verifiedAt,
    ) {}
}
