<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;

final class CertificationEntityTest extends TestCase
{
    public function test_create_returns_certification(): void
    {
        $id = CertificationId::generate();
        $profileId = SkillProfileId::generate();
        $issueDate = new DateTimeImmutable('2026-01-15');

        $cert = Certification::create($id, $profileId, 'AWS Certified', 'Amazon', $issueDate);

        $this->assertSame($id, $cert->id());
        $this->assertSame($profileId, $cert->skillProfileId());
        $this->assertSame('AWS Certified', $cert->name());
        $this->assertSame('Amazon', $cert->issuer());
        $this->assertSame($issueDate, $cert->issueDate());
        $this->assertNull($cert->expiryDate());
        $this->assertNull($cert->credentialUrl());
        $this->assertNull($cert->verificationCode());
    }

    public function test_create_with_all_optional_fields(): void
    {
        $id = CertificationId::generate();
        $profileId = SkillProfileId::generate();
        $issueDate = new DateTimeImmutable('2026-01-15');
        $expiryDate = new DateTimeImmutable('2028-01-15');

        $cert = Certification::create(
            $id, $profileId, 'Google Cloud', 'Google', $issueDate,
            $expiryDate, 'https://example.com/cert', 'VER123'
        );

        $this->assertSame($expiryDate, $cert->expiryDate());
        $this->assertSame('https://example.com/cert', $cert->credentialUrl());
        $this->assertSame('VER123', $cert->verificationCode());
    }

    public function test_reconstitute_restores_certification(): void
    {
        $id = CertificationId::generate();
        $profileId = SkillProfileId::generate();
        $issueDate = new DateTimeImmutable('2026-01-15');

        $cert = Certification::reconstitute($id, $profileId, 'MCSA', 'Microsoft', $issueDate, null, null, null);

        $this->assertSame('MCSA', $cert->name());
        $this->assertSame('Microsoft', $cert->issuer());
    }

    public function test_is_expired_returns_true_when_expired(): void
    {
        $cert = Certification::create(
            CertificationId::generate(),
            SkillProfileId::generate(),
            'Old Cert',
            'Some Issuer',
            new DateTimeImmutable('2020-01-01'),
            new DateTimeImmutable('2021-01-01')
        );

        $this->assertTrue($cert->isExpired());
    }

    public function test_is_expired_returns_false_when_not_expired(): void
    {
        $cert = Certification::create(
            CertificationId::generate(),
            SkillProfileId::generate(),
            'New Cert',
            'Some Issuer',
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2030-01-01')
        );

        $this->assertFalse($cert->isExpired());
    }

    public function test_is_expired_returns_false_when_no_expiry(): void
    {
        $cert = Certification::create(
            CertificationId::generate(),
            SkillProfileId::generate(),
            'No Expiry Cert',
            'Some Issuer',
            new DateTimeImmutable('2026-01-01')
        );

        $this->assertFalse($cert->isExpired());
    }
}
