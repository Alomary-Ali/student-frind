<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Enums\UserStatus;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function save(User $user): void
    {
        EloquentUser::updateOrCreate(
            ['id' => $user->id()->value()],
            [
                'academic_id' => $user->academicId()->value(),
                'email' => $user->email()->value(),
                'first_name' => $user->name()->firstName(),
                'last_name' => $user->name()->lastName(),
                'password_hash' => $user->passwordHash(),
                'role' => $user->role()->value,
                'status' => $user->status()->value,
                'email_verified_at' => $user->emailVerifiedAt(),
            ],
        );
    }

    public function findById(UserId $id): ?User
    {
        $eloquentUser = EloquentUser::find($id->value());

        if ($eloquentUser === null) {
            return null;
        }

        return $this->toDomain($eloquentUser);
    }

    public function findByEmail(EmailAddress $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->value())->first();

        if ($eloquentUser === null) {
            return null;
        }

        return $this->toDomain($eloquentUser);
    }

    public function existsByEmail(EmailAddress $email): bool
    {
        return EloquentUser::where('email', $email->value())->exists();
    }

    public function findByAcademicId(AcademicId $academicId): ?User
    {
        $eloquentUser = EloquentUser::where('academic_id', $academicId->value())->first();

        if ($eloquentUser === null) {
            return null;
        }

        return $this->toDomain($eloquentUser);
    }

    public function existsByAcademicId(AcademicId $academicId): bool
    {
        return EloquentUser::where('academic_id', $academicId->value())->exists();
    }

    public function getFailedLoginAttempts(string $userId): int
    {
        return EloquentUser::where('id', $userId)->value('failed_login_attempts') ?? 0;
    }

    public function incrementFailedAttempts(string $userId): int
    {
        $current = $this->getFailedLoginAttempts($userId);
        $newAttempts = $current + 1;

        EloquentUser::where('id', $userId)->update(['failed_login_attempts' => $newAttempts]);

        return $newAttempts;
    }

    public function resetFailedAttempts(string $userId): void
    {
        EloquentUser::where('id', $userId)->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    public function isAccountLocked(string $userId): bool
    {
        $lockedUntil = $this->getLockedUntil($userId);

        if ($lockedUntil === null) {
            return false;
        }

        return $lockedUntil > new DateTimeImmutable;
    }

    public function getLockedUntil(string $userId): ?DateTimeImmutable
    {
        $value = EloquentUser::where('id', $userId)->value('locked_until');

        if ($value === null) {
            return null;
        }

        if ($value instanceof \Illuminate\Support\Carbon) {
            return new DateTimeImmutable($value->toIso8601String());
        }

        return new DateTimeImmutable($value);
    }

    public function setLockedUntil(string $userId, ?DateTimeImmutable $lockedUntil): void
    {
        EloquentUser::where('id', $userId)->update([
            'locked_until' => $lockedUntil?->format('Y-m-d H:i:s'),
        ]);
    }

    private function toDomain(EloquentUser $eloquentUser): User
    {
        return User::reconstitute(
            id: UserId::fromString($eloquentUser->id),
            academicId: AcademicId::of($eloquentUser->academic_id),
            email: EmailAddress::fromString($eloquentUser->email),
            name: FullName::of($eloquentUser->first_name, $eloquentUser->last_name),
            passwordHash: $eloquentUser->password_hash,
            role: UserRole::from($eloquentUser->role),
            status: UserStatus::from($eloquentUser->status),
            emailVerifiedAt: $eloquentUser->email_verified_at
                ? new DateTimeImmutable($eloquentUser->email_verified_at->toIso8601String())
                : null,
            createdAt: new DateTimeImmutable($eloquentUser->created_at->toIso8601String()),
        );
    }
}
