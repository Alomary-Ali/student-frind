<?php

declare(strict_types=1);

namespace Modules\Shared\Application\UseCases;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Enums\UserStatus;
use Modules\Shared\Domain\Exceptions\AccountLockedException;
use Modules\Shared\Domain\Exceptions\InvalidCredentialsException;
use Modules\Shared\Domain\Exceptions\UserSuspendedException;
use Modules\Shared\Domain\ValueObjects\AcademicId;

final readonly class AuthenticateUser
{
    private const MAX_FAILED_ATTEMPTS = 5;
    private const LOCKOUT_DURATION_MINUTES = 15;

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(string $academicIdStr, string $password): bool
    {
        $academicId = new AcademicId($academicIdStr);
        $user = $this->userRepository->findByAcademicId($academicId);

        if ($user === null) {
            throw InvalidCredentialsException::create();
        }

        $userId = $user->id()->value();

        // Check if account is locked
        $this->checkAccountLock($userId);

        if (!Hash::check($password, $user->passwordHash())) {
            $this->incrementFailedAttempts($userId);
            throw InvalidCredentialsException::create();
        }

        if (!$user->canLogin()) {
            if ($user->status() === UserStatus::Suspended) {
                throw UserSuspendedException::forUser($userId);
            }
            throw InvalidCredentialsException::create();
        }

        // Reset failed attempts on successful login
        $this->userRepository->resetFailedAttempts($userId);

        // Log the user in using Laravel's session auth
        Auth::loginUsingId($userId);

        return true;
    }

    private function checkAccountLock(string $userId): void
    {
        if ($this->userRepository->isAccountLocked($userId)) {
            $lockedUntil = $this->userRepository->getLockedUntil($userId);
            $remainingMinutes = (int) ceil(
                max(0, $lockedUntil->getTimestamp() - time()) / 60
            );
            throw AccountLockedException::withMessage(
                "تم قفل حسابك بسبب محاولات تسجيل دخول فاشلة متعددة. يرجى المحاولة بعد {$remainingMinutes} دقيقة"
            );
        }

        // Auto-unlock if lockout period has expired
        $lockedUntil = $this->userRepository->getLockedUntil($userId);
        if ($lockedUntil !== null && $lockedUntil < new \DateTimeImmutable()) {
            $this->userRepository->resetFailedAttempts($userId);
        }
    }

    private function incrementFailedAttempts(string $userId): void
    {
        $newAttempts = $this->userRepository->incrementFailedAttempts($userId);

        if ($newAttempts >= self::MAX_FAILED_ATTEMPTS) {
            $lockedUntil = new \DateTimeImmutable('+' . self::LOCKOUT_DURATION_MINUTES . ' minutes');
            $this->userRepository->setLockedUntil($userId, $lockedUntil);
        }
    }
}
