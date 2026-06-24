<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class DocumentVerified
{
    public function __construct(
        public string $documentId,
        public string $verifierId,
        public DateTimeImmutable $occurredAt,
    ) {}
}
