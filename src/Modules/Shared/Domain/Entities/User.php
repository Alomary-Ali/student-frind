<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Entities;

use DateTimeImmutable;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Enums\UserStatus;
use Modules\Shared\Domain\Events\UserEmailVerified;
use Modules\Shared\Domain\Events\UserRegistered;
use Modules\Shared\Domain\Events\UserRoleAssigned;
use Modules\Shared\Domain\Exceptions\UserSuspendedException;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;

/**
 * User — Aggregate Root of the Shared module.
 *
 * Represents the identity of any person using the SSP platform.
 * This entity intentionally contains only identity data.
 * Domain-specific data (e.g., Student academic plan) lives in its own module.
 *
 * Rules:
 * - User is created via the static factory `register()`
 * - Business methods enforce invariants before mutating state
 * - Domain events are raised internally and released by the Application layer
 */
final class User
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly UserId $id,
        private readonly AcademicId $academicId,
        private EmailAddress $email,
        private FullName $name,
        private string $passwordHash,
        private UserRole $role,
        private UserStatus $status,
        private ?DateTimeImmutable $emailVerifiedAt,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    /**
     * Factory method — the only way to create a new User.
     * Raises UserRegistered domain event.
     */
    public static function register(
        UserId $id,
        AcademicId $academicId,
        EmailAddress $email,
        FullName $name,
        string $passwordHash,
        UserRole $role = UserRole::Student,
    ): self {
        $user = new self(
            id: $id,
            academicId: $academicId,
            email: $email,
            name: $name,
            passwordHash: $passwordHash,
            role: $role,
            status: UserStatus::PendingVerification,
            emailVerifiedAt: null,
            createdAt: new DateTimeImmutable,
        );

        $user->raise(new UserRegistered(
            userId: $id->value(),
            email: $email->value(),
            fullName: $name->full(),
            role: $role->value,
            occurredAt: new DateTimeImmutable,
        ));

        return $user;
    }

    /**
     * Reconstitute a User from persistence (no events raised).
     */
    public static function reconstitute(
        UserId $id,
        AcademicId $academicId,
        EmailAddress $email,
        FullName $name,
        string $passwordHash,
        UserRole $role,
        UserStatus $status,
        ?DateTimeImmutable $emailVerifiedAt,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            id: $id,
            academicId: $academicId,
            email: $email,
            name: $name,
            passwordHash: $passwordHash,
            role: $role,
            status: $status,
            emailVerifiedAt: $emailVerifiedAt,
            createdAt: $createdAt,
        );
    }

    // -------------------------------------------------------------------------
    // Business Methods
    // -------------------------------------------------------------------------

    public function verifyEmail(): void
    {
        if ($this->emailVerifiedAt !== null) {
            return; // Already verified — idempotent
        }

        $this->emailVerifiedAt = new DateTimeImmutable;
        $this->status = UserStatus::Active;

        $this->raise(new UserEmailVerified(
            userId: $this->id->value(),
            email: $this->email->value(),
            verifiedAt: $this->emailVerifiedAt,
        ));
    }

    public function assignRole(UserRole $newRole): void
    {
        if ($this->status === UserStatus::Suspended) {
            throw UserSuspendedException::forUser($this->id->value());
        }

        $previousRole = $this->role;
        $this->role = $newRole;

        $this->raise(new UserRoleAssigned(
            userId: $this->id->value(),
            previousRole: $previousRole->value,
            newRole: $newRole->value,
            occurredAt: new DateTimeImmutable,
        ));
    }

    public function suspend(): void
    {
        $this->status = UserStatus::Suspended;
    }

    public function activate(): void
    {
        $this->status = UserStatus::Active;
    }

    public function changeEmail(EmailAddress $newEmail): void
    {
        $this->email = $newEmail;
        $this->emailVerifiedAt = null;
        $this->status = UserStatus::PendingVerification;
    }

    public function updateName(FullName $newName): void
    {
        $this->name = $newName;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    public function canLogin(): bool
    {
        return $this->status->isAllowedToLogin();
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function id(): UserId
    {
        return $this->id;
    }

    public function academicId(): AcademicId
    {
        return $this->academicId;
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }

    public function name(): FullName
    {
        return $this->name;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function status(): UserStatus
    {
        return $this->status;
    }

    public function emailVerifiedAt(): ?DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    // -------------------------------------------------------------------------
    // Domain Event Management
    // -------------------------------------------------------------------------

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
