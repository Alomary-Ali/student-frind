<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Auth;

use Modules\Shared\Domain\Contracts\AuthServiceInterface;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;

final class SanctumAuthService implements AuthServiceInterface
{
    public function generateToken(User $user): string
    {
        $eloquentUser = EloquentUser::findOrFail($user->id()->value());

        $tokenResult = $eloquentUser->createToken('auth_token');

        return $tokenResult->plainTextToken;
    }
}
