<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Events;

use DateTimeImmutable;

final readonly class CareerPathCreated
{
    public function __construct(
        public string $pathId,
        public string $title,
        public string $targetRole,
        public DateTimeImmutable $occurredAt,
    ) {}
}
