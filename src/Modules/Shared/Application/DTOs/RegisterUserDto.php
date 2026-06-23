<?php

declare(strict_types=1);

namespace Modules\Shared\Application\DTOs;

final readonly class RegisterUserDto
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $password,
        public string $role,
    ) {}
}
