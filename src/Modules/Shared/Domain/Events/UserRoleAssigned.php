<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Events;

use DateTimeImmutable;

final readonly class UserRoleAssigned
{
    public function __construct(
        public string $userId,
        public string $previousRole,
        public string $newRole,
        public DateTimeImmutable $occurredAt,
    ) {}
}
