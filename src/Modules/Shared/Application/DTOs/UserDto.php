<?php

declare(strict_types=1);

namespace Modules\Shared\Application\DTOs;

final readonly class UserDto
{
    public function __construct(
        public string $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $fullName,
        public string $role,
        public string $status,
        public ?string $emailVerifiedAt,
        public string $createdAt,
    ) {}
}
