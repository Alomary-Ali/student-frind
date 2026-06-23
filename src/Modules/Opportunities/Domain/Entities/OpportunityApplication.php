<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Entities;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Enums\ApplicationStatus;
use Modules\Opportunities\Domain\ValueObjects\ApplicationId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

final class OpportunityApplication
{
    private function __construct(
        private readonly ApplicationId $id,
        private readonly OpportunityId $opportunityId,
        private readonly string $studentId,
        private ApplicationStatus $status,
        private ?DateTimeImmutable $appliedAt,
        private ?string $notes,
    ) {}

    public static function create(
        ApplicationId $id,
        OpportunityId $opportunityId,
        string $studentId,
        ?string $notes = null,
    ): self {
        return new self(
            $id,
            $opportunityId,
            $studentId,
            ApplicationStatus::SAVED,
            null,
            $notes,
        );
    }

    public static function reconstitute(
        ApplicationId $id,
        OpportunityId $opportunityId,
        string $studentId,
        ApplicationStatus $status,
        ?DateTimeImmutable $appliedAt,
        ?string $notes,
    ): self {
        return new self(
            $id,
            $opportunityId,
            $studentId,
            $status,
            $appliedAt,
            $notes,
        );
    }

    public function id(): ApplicationId
    {
        return $this->id;
    }

    public function opportunityId(): OpportunityId
    {
        return $this->opportunityId;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function status(): ApplicationStatus
    {
        return $this->status;
    }

    public function appliedAt(): ?DateTimeImmutable
    {
        return $this->appliedAt;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function submit(): void
    {
        $this->status = ApplicationStatus::APPLIED;
        $this->appliedAt = new DateTimeImmutable;
    }

    public function updateStatus(ApplicationStatus $status): void
    {
        $this->status = $status;

        if ($status === ApplicationStatus::APPLIED && $this->appliedAt === null) {
            $this->appliedAt = new DateTimeImmutable;
        }
    }

    public function updateNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function isFinalStatus(): bool
    {
        return $this->status->isFinal();
    }
}
