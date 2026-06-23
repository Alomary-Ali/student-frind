<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Domain\ValueObjects;

use InvalidArgumentException;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\RoleId;
use PHPUnit\Framework\TestCase;

final class SharedValueObjectsTest extends TestCase
{
    public function test_academic_id_accepts_valid_8_digit_string(): void
    {
        $id = AcademicId::of('12345678');
        $this->assertSame('12345678', $id->value());
    }

    public function test_academic_id_rejects_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Academic ID cannot be empty');
        AcademicId::of('');
    }

    public function test_academic_id_rejects_non_8_digit_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Academic ID must be 8 digits');
        AcademicId::of('1234567');
    }

    public function test_academic_id_equals_returns_true_for_same_value(): void
    {
        $a = AcademicId::of('12345678');
        $b = AcademicId::of('12345678');
        $this->assertTrue($a->equals($b));
    }

    public function test_academic_id_equals_returns_false_for_different_value(): void
    {
        $a = AcademicId::of('12345678');
        $b = AcademicId::of('87654321');
        $this->assertFalse($a->equals($b));
    }

    public function test_academic_id_to_string_returns_value(): void
    {
        $id = AcademicId::of('12345678');
        $this->assertSame('12345678', (string) $id);
    }

    public function test_role_id_generates_valid_uuid(): void
    {
        $id = RoleId::generate();
        $this->assertNotEmpty($id->value());
        $this->assertTrue(preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id->value()) === 1);
    }

    public function test_role_id_from_string_accepts_valid_uuid(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $id = RoleId::fromString($uuid);
        $this->assertSame($uuid, $id->value());
    }

    public function test_role_id_from_string_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RoleId::fromString('not-a-uuid');
    }

    public function test_role_id_from_string_rejects_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RoleId::fromString('');
    }

    public function test_role_id_equals_returns_true_for_same_value(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $a = RoleId::fromString($uuid);
        $b = RoleId::fromString($uuid);
        $this->assertTrue($a->equals($b));
    }

    public function test_role_id_equals_returns_false_for_different_value(): void
    {
        $a = RoleId::fromString('123e4567-e89b-12d3-a456-426614174000');
        $b = RoleId::fromString('223e4567-e89b-12d3-a456-426614174001');
        $this->assertFalse($a->equals($b));
    }

    public function test_permission_id_generates_valid_uuid(): void
    {
        $id = PermissionId::generate();
        $this->assertNotEmpty($id->value());
        $this->assertTrue(preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id->value()) === 1);
    }

    public function test_permission_id_from_string_accepts_valid_uuid(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $id = PermissionId::fromString($uuid);
        $this->assertSame($uuid, $id->value());
    }

    public function test_permission_id_from_string_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        PermissionId::fromString('bad-uuid');
    }

    public function test_permission_id_equals_returns_true_for_same_value(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $a = PermissionId::fromString($uuid);
        $b = PermissionId::fromString($uuid);
        $this->assertTrue($a->equals($b));
    }

    public function test_permission_id_equals_returns_false_for_different_value(): void
    {
        $a = PermissionId::fromString('123e4567-e89b-12d3-a456-426614174000');
        $b = PermissionId::fromString('223e4567-e89b-12d3-a456-426614174001');
        $this->assertFalse($a->equals($b));
    }
}
