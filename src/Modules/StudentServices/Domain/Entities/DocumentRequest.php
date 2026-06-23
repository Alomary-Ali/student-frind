<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\DocumentStatus;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\Events\DocumentRequested;
use Modules\StudentServices\Domain\ValueObjects\DocumentRequestId;

final class DocumentRequest
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly DocumentRequestId $id,
        private readonly string $studentId,
        private readonly DocumentType $documentType,
        private DocumentStatus $status,
        private ?string $notes,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        DocumentRequestId $id,
        string $studentId,
        DocumentType $documentType,
        ?string $notes = null,
    ): self {
        $now = new DateTimeImmutable;

        $request = new self(
            $id,
            $studentId,
            $documentType,
            DocumentStatus::PENDING,
            $notes,
            $now,
            $now,
        );

        $request->raise(new DocumentRequested(
            $id->value(),
            $studentId,
            $documentType->value,
            $now,
        ));

        return $request;
    }

    public static function reconstitute(
        DocumentRequestId $id,
        string $studentId,
        DocumentType $documentType,
        DocumentStatus $status,
        ?string $notes,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $documentType,
            $status,
            $notes,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): DocumentRequestId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function documentType(): DocumentType
    {
        return $this->documentType;
    }

    public function status(): DocumentStatus
    {
        return $this->status;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function approve(): void
    {
        $this->status = DocumentStatus::GENERATED;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function reject(): void
    {
        $this->status = DocumentStatus::EXPIRED;
        $this->updatedAt = new DateTimeImmutable;
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
