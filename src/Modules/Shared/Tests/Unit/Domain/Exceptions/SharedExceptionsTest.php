<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Domain\Exceptions;

use DomainException;
use Exception;
use Modules\Shared\Domain\Exceptions\AccountLockedException;
use Modules\Shared\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Shared\Domain\Exceptions\InvalidCredentialsException;
use Modules\Shared\Domain\Exceptions\UserEmailNotVerifiedException;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class SharedExceptionsTest extends TestCase
{
    public function test_user_not_found_exception_with_id(): void
    {
        $e = UserNotFoundException::withId('user-123');

        $this->assertInstanceOf(DomainException::class, $e);
        $this->assertStringContainsString('user-123', $e->getMessage());
        $this->assertStringContainsString('not found', $e->getMessage());
    }

    public function test_user_not_found_exception_with_email(): void
    {
        $e = UserNotFoundException::withEmail('test@example.com');

        $this->assertInstanceOf(DomainException::class, $e);
        $this->assertStringContainsString('test@example.com', $e->getMessage());
    }

    public function test_invalid_credentials_exception(): void
    {
        $e = InvalidCredentialsException::create();

        $this->assertInstanceOf(RuntimeException::class, $e);
        $this->assertSame('Invalid email or password.', $e->getMessage());
    }

    public function test_email_already_taken_exception(): void
    {
        $e = EmailAlreadyTakenException::forEmail('taken@example.com');

        $this->assertInstanceOf(DomainException::class, $e);
        $this->assertStringContainsString('taken@example.com', $e->getMessage());
        $this->assertStringContainsString('already registered', $e->getMessage());
    }

    public function test_user_email_not_verified_exception(): void
    {
        $e = UserEmailNotVerifiedException::forUser('user-456');

        $this->assertInstanceOf(RuntimeException::class, $e);
        $this->assertStringContainsString('user-456', $e->getMessage());
        $this->assertStringContainsString('not verified', $e->getMessage());
    }

    public function test_account_locked_exception_for_user(): void
    {
        $e = AccountLockedException::forUser('user-789');

        $this->assertInstanceOf(Exception::class, $e);
        $this->assertStringContainsString('user-789', $e->getMessage());
        $this->assertStringContainsString('locked', $e->getMessage());
    }

    public function test_account_locked_exception_with_message(): void
    {
        $e = AccountLockedException::withMessage('Custom lock message');

        $this->assertInstanceOf(Exception::class, $e);
        $this->assertSame('Custom lock message', $e->getMessage());
    }
}
