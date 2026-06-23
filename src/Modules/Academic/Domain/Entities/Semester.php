<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\SemesterId;

final class Semester
{
    private function __construct(
        private readonly SemesterId $id,
        private readonly string $name,
        private readonly string $code,
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
        private readonly bool $isActive,
        private readonly ?string $institutionId,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        SemesterId $id,
        string $name,
        string $code,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        ?string $institutionId = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            code: $code,
            startDate: $startDate,
            endDate: $endDate,
            isActive: true,
            institutionId: $institutionId,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        SemesterId $id,
        string $name,
        string $code,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        bool $isActive,
        ?string $institutionId,
        DateTimeImmutable $createdAt,
    ): self {
        return new self($id, $name, $code, $startDate, $endDate, $isActive, $institutionId, $createdAt);
    }

    public function isCurrentlyActive(): bool
    {
        $now = new DateTimeImmutable();

        return $this->isActive
            && $now >= $this->startDate
            && $now <= $this->endDate;
    }

    public function id(): SemesterId { return $this->id; }
    public function name(): string { return $this->name; }
    public function code(): string { return $this->code; }
    public function startDate(): DateTimeImmutable { return $this->startDate; }
    public function endDate(): DateTimeImmutable { return $this->endDate; }
    public function isActive(): bool { return $this->isActive; }
    public function institutionId(): ?string { return $this->institutionId; }
    public function createdAt(): DateTimeImmutable { return $this->createdAt; }
}
