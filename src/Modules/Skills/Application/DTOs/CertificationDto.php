<?php

declare(strict_types=1);

namespace Modules\Skills\Application\DTOs;

final readonly class CertificationDto
{
    public function __construct(
        public string $id,
        public string $skillProfileId,
        public string $name,
        public string $issuer,
        public string $issueDate,
        public ?string $expiryDate,
        public ?string $credentialUrl,
        public ?string $verificationCode,
        public bool $isExpired,
    ) {
    }
}
