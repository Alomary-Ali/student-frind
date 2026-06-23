<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Entities;

use DateTimeImmutable;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;

final class Certification
{
    private function __construct(
        private readonly CertificationId $id,
        private readonly SkillProfileId $skillProfileId,
        private string $name,
        private string $issuer,
        private DateTimeImmutable $issueDate,
        private ?DateTimeImmutable $expiryDate,
        private ?string $credentialUrl,
        private ?string $verificationCode,
    ) {}

    public static function create(
        CertificationId $id,
        SkillProfileId $skillProfileId,
        string $name,
        string $issuer,
        DateTimeImmutable $issueDate,
        ?DateTimeImmutable $expiryDate = null,
        ?string $credentialUrl = null,
        ?string $verificationCode = null,
    ): self {
        return new self(
            $id,
            $skillProfileId,
            $name,
            $issuer,
            $issueDate,
            $expiryDate,
            $credentialUrl,
            $verificationCode,
        );
    }

    public static function reconstitute(
        CertificationId $id,
        SkillProfileId $skillProfileId,
        string $name,
        string $issuer,
        DateTimeImmutable $issueDate,
        ?DateTimeImmutable $expiryDate,
        ?string $credentialUrl,
        ?string $verificationCode,
    ): self {
        return new self(
            $id,
            $skillProfileId,
            $name,
            $issuer,
            $issueDate,
            $expiryDate,
            $credentialUrl,
            $verificationCode,
        );
    }

    public function id(): CertificationId
    {
        return $this->id;
    }

    public function skillProfileId(): SkillProfileId
    {
        return $this->skillProfileId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function issuer(): string
    {
        return $this->issuer;
    }

    public function issueDate(): DateTimeImmutable
    {
        return $this->issueDate;
    }

    public function expiryDate(): ?DateTimeImmutable
    {
        return $this->expiryDate;
    }

    public function credentialUrl(): ?string
    {
        return $this->credentialUrl;
    }

    public function verificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function isExpired(): bool
    {
        if ($this->expiryDate === null) {
            return false;
        }

        return $this->expiryDate < new DateTimeImmutable;
    }
}
