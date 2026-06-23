<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\Events\AlertCreated;
use Modules\Academic\Domain\Events\AlertResolved;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\StudentId;

final class AcademicAlert
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly AlertId $id,
        private readonly StudentId $studentId,
        private readonly AlertType $alertType,
        private readonly AlertSeverity $severity,
        private readonly string $message,
        private readonly ?array $metadata,
        private bool $isResolved,
        private readonly DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $resolvedAt,
        private ?string $resolvedBy,
    ) {}

    public static function create(
        AlertId $id,
        StudentId $studentId,
        AlertType $alertType,
        AlertSeverity $severity,
        string $message,
        ?array $metadata = null,
    ): self {
        $alert = new self(
            id: $id,
            studentId: $studentId,
            alertType: $alertType,
            severity: $severity,
            message: $message,
            metadata: $metadata,
            isResolved: false,
            createdAt: new DateTimeImmutable(),
            resolvedAt: null,
            resolvedBy: null,
        );

        $alert->raise(new AlertCreated(
            alertId: $id->value(),
            studentId: $studentId->value(),
            alertType: $alertType->value,
            severity: $severity->value,
            occurredAt: new DateTimeImmutable(),
        ));

        return $alert;
    }

    public static function reconstitute(
        AlertId $id,
        StudentId $studentId,
        AlertType $alertType,
        AlertSeverity $severity,
        string $message,
        ?array $metadata,
        bool $isResolved,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $resolvedAt,
        ?string $resolvedBy,
    ): self {
        return new self(
            id: $id,
            studentId: $studentId,
            alertType: $alertType,
            severity: $severity,
            message: $message,
            metadata: $metadata,
            isResolved: $isResolved,
            createdAt: $createdAt,
            resolvedAt: $resolvedAt,
            resolvedBy: $resolvedBy,
        );
    }

    public function resolve(string $resolvedBy): void
    {
        if ($this->isResolved) {
            return;
        }

        $this->isResolved = true;
        $this->resolvedAt = new DateTimeImmutable();
        $this->resolvedBy = $resolvedBy;

        $this->raise(new AlertResolved(
            alertId: $this->id->value(),
            studentId: $this->studentId->value(),
            resolvedBy: $resolvedBy,
            resolvedAt: $this->resolvedAt,
        ));
    }

    public function id(): AlertId { return $this->id; }
    public function studentId(): StudentId { return $this->studentId; }
    public function alertType(): AlertType { return $this->alertType; }
    public function severity(): AlertSeverity { return $this->severity; }
    public function message(): string { return $this->message; }
    public function metadata(): ?array { return $this->metadata; }
    public function isResolved(): bool { return $this->isResolved; }
    public function createdAt(): DateTimeImmutable { return $this->createdAt; }
    public function resolvedAt(): ?DateTimeImmutable { return $this->resolvedAt; }
    public function resolvedBy(): ?string { return $this->resolvedBy; }

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
