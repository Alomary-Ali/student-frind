<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

use Modules\Shared\Domain\Entities\User;

interface AuthServiceInterface
{
    public function generateToken(User $user): string;
}
