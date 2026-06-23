<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit;

use Modules\Shared\Domain\Entities\User;
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
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function test_user_can_be_registered(): void
    {
        $id = UserId::generate();
        $academicId = AcademicId::of('12345678');
        $email = EmailAddress::fromString('student@ssp.com');
        $name = FullName::of('Jane', 'Doe');
        $passwordHash = 'hashed_password';

        $user = User::register($id, $academicId, $email, $name, $passwordHash, UserRole::Student);

        $this->assertSame($id->value(), $user->id()->value());
        $this->assertSame($email->value(), $user->email()->value());
        $this->assertSame($name->full(), $user->name()->full());
        $this->assertSame($passwordHash, $user->passwordHash());
        $this->assertSame(UserRole::Student, $user->role());
        $this->assertSame(UserStatus::PendingVerification, $user->status());
        $this->assertNull($user->emailVerifiedAt());

        $events = $user->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegistered::class, $events[0]);
    }

    public function test_user_email_can_be_verified(): void
    {
        $id = UserId::generate();
        $academicId = AcademicId::of('12345678');
        $email = EmailAddress::fromString('student@ssp.com');
        $name = FullName::of('Jane', 'Doe');
        $passwordHash = 'hashed_password';

        $user = User::register($id, $academicId, $email, $name, $passwordHash);
        $user->releaseEvents(); // Clear registration events

        $user->verifyEmail();

        $this->assertSame(UserStatus::Active, $user->status());
        $this->assertNotNull($user->emailVerifiedAt());
        $this->assertTrue($user->hasVerifiedEmail());

        $events = $user->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserEmailVerified::class, $events[0]);
    }

    public function test_role_can_be_assigned_to_active_user(): void
    {
        $id = UserId::generate();
        $academicId = AcademicId::of('12345678');
        $email = EmailAddress::fromString('student@ssp.com');
        $name = FullName::of('Jane', 'Doe');
        $passwordHash = 'hashed_password';

        $user = User::register($id, $academicId, $email, $name, $passwordHash);
        $user->verifyEmail();
        $user->releaseEvents();

        $user->assignRole(UserRole::Advisor);

        $this->assertSame(UserRole::Advisor, $user->role());

        $events = $user->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRoleAssigned::class, $events[0]);
    }

    public function test_role_assignment_throws_exception_if_user_suspended(): void
    {
        $id = UserId::generate();
        $academicId = AcademicId::of('12345678');
        $email = EmailAddress::fromString('student@ssp.com');
        $name = FullName::of('Jane', 'Doe');
        $passwordHash = 'hashed_password';

        $user = User::register($id, $academicId, $email, $name, $passwordHash);
        $user->suspend();

        $this->expectException(UserSuspendedException::class);
        $user->assignRole(UserRole::Advisor);
    }
}
