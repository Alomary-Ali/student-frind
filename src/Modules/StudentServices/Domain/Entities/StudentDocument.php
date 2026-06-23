<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\DocumentStatus;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\Events\DocumentGenerated;
use Modules\StudentServices\Domain\Events\DocumentVerified;
use Modules\StudentServices\Domain\ValueObjects\DocumentId;

final class StudentDocument
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly DocumentId $id,
        private readonly string $studentId,
        private readonly DocumentType $type,
        private string $title,
        private ?string $filePath,
        private DocumentStatus $status,
        private ?string $verificationCode,
        private array $metadata,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        DocumentId $id,
        string $studentId,
        DocumentType $type,
        string $title,
        array $metadata = [],
    ): self {
        $now = new DateTimeImmutable;

        return new self(
            $id,
            $studentId,
            $type,
            $title,
            null,
            DocumentStatus::PENDING,
            null,
            $metadata,
            $now,
            $now,
        );
    }

    public static function reconstitute(
        DocumentId $id,
        string $studentId,
        DocumentType $type,
        string $title,
        ?string $filePath,
        DocumentStatus $status,
        ?string $verificationCode,
        array $metadata,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $type,
            $title,
            $filePath,
            $status,
            $verificationCode,
            $metadata,
            $createdAt,
            $updatedAt,
        );
    }

    public function generate(string $filePath, string $verificationCode): void
    {
        $this->filePath = $filePath;
        $this->verificationCode = $verificationCode;
        $this->status = DocumentStatus::GENERATED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new DocumentGenerated(
            $this->id->value(),
            $this->studentId,
            $this->type->value,
            $filePath,
            $verificationCode,
            $this->updatedAt,
        ));
    }

    public function verify(string $verifierId): void
    {
        $this->status = DocumentStatus::VERIFIED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new DocumentVerified(
            $this->id->value(),
            $verifierId,
            $this->updatedAt,
        ));
    }

    public function id(): DocumentId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function type(): DocumentType
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function filePath(): ?string
    {
        return $this->filePath;
    }

    public function status(): DocumentStatus
    {
        return $this->status;
    }

    public function verificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

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
