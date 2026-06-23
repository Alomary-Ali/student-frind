<?php

declare(strict_types=1);

namespace Modules\Shared\Application\DTOs;

final readonly class AuthTokenDto
{
    public function __construct(
        public UserDto $user,
        public string $token,
        public string $tokenType = 'Bearer',
    ) {}
}
