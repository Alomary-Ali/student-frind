<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(UserId $id): ?User;

    public function findByEmail(EmailAddress $email): ?User;

    public function findByAcademicId(AcademicId $academicId): ?User;

    public function existsByEmail(EmailAddress $email): bool;

    public function existsByAcademicId(AcademicId $academicId): bool;

    public function getFailedLoginAttempts(string $userId): int;

    public function incrementFailedAttempts(string $userId): int;

    public function resetFailedAttempts(string $userId): void;

    public function isAccountLocked(string $userId): bool;

    public function getLockedUntil(string $userId): ?\DateTimeImmutable;

    public function setLockedUntil(string $userId, ?\DateTimeImmutable $lockedUntil): void;
}
