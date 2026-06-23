<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit;

use Modules\Shared\Domain\Exceptions\InvalidEmailAddressException;
use Modules\Shared\Domain\Exceptions\InvalidFullNameException;
use Modules\Shared\Domain\Exceptions\InvalidUserIdException;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class ValueObjectsTest extends TestCase
{
    public function test_user_id_accepts_valid_uuid(): void
    {
        $uuidStr = '123e4567-e89b-12d3-a456-426614174000';
        $userId = UserId::fromString($uuidStr);
        $this->assertSame($uuidStr, $userId->value());
    }

    public function test_user_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidUserIdException::class);
        UserId::fromString('invalid-uuid');
    }

    public function test_user_id_generates_valid_uuid(): void
    {
        $userId = UserId::generate();
        $this->assertNotEmpty($userId->value());
        $this->assertTrue(preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $userId->value()) === 1);
    }

    public function test_email_address_accepts_valid_email(): void
    {
        $email = EmailAddress::fromString('  TEST@example.COM  ');
        $this->assertSame('test@example.com', $email->value());
    }

    public function test_email_address_rejects_invalid_email(): void
    {
        $this->expectException(InvalidEmailAddressException::class);
        EmailAddress::fromString('invalid-email');
    }

    public function test_full_name_accepts_valid_name(): void
    {
        $name = FullName::of('John', 'Doe');
        $this->assertSame('John', $name->firstName());
        $this->assertSame('Doe', $name->lastName());
        $this->assertSame('John Doe', $name->full());
    }

    public function test_full_name_rejects_empty_names(): void
    {
        $this->expectException(InvalidFullNameException::class);
        FullName::of('', 'Doe');
    }

    public function test_full_name_rejects_too_short_names(): void
    {
        $this->expectException(InvalidFullNameException::class);
        FullName::of('J', 'Doe');
    }

    public function test_full_name_rejects_too_long_names(): void
    {
        $this->expectException(InvalidFullNameException::class);
        FullName::of(str_repeat('A', 51), 'Doe');
    }
}
