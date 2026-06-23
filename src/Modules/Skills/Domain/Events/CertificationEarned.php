<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Events;

use DateTimeImmutable;

final readonly class CertificationEarned
{
    public function __construct(
        public string $certificationId,
        public string $profileId,
        public string $name,
        public string $issuer,
        public DateTimeImmutable $occurredAt,
    ) {}
}
