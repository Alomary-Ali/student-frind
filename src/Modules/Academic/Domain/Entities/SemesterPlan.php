<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;

final class SemesterPlan
{
    private function __construct(
        private readonly SemesterPlanId $id,
        private readonly StudentId $studentId,
        private readonly SemesterId $semesterId,
        private array $plannedCourses,
        private int $totalCredits,
        private string $status,
        private ?string $notes,
        private ?DateTimeImmutable $submittedAt,
        private ?string $approvedBy,
        private ?DateTimeImmutable $approvedAt,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        SemesterPlanId $id,
        StudentId $studentId,
        SemesterId $semesterId,
        array $plannedCourses,
        int $totalCredits,
        ?string $notes = null,
    ): self {
        return new self(
            id: $id,
            studentId: $studentId,
            semesterId: $semesterId,
            plannedCourses: $plannedCourses,
            totalCredits: $totalCredits,
            status: 'draft',
            notes: $notes,
            submittedAt: null,
            approvedBy: null,
            approvedAt: null,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        SemesterPlanId $id,
        StudentId $studentId,
        SemesterId $semesterId,
        array $plannedCourses,
        int $totalCredits,
        string $status,
        ?string $notes,
        ?DateTimeImmutable $submittedAt,
        ?string $approvedBy,
        ?DateTimeImmutable $approvedAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            studentId: $studentId,
            semesterId: $semesterId,
            plannedCourses: $plannedCourses,
            totalCredits: $totalCredits,
            status: $status,
            notes: $notes,
            submittedAt: $submittedAt,
            approvedBy: $approvedBy,
            approvedAt: $approvedAt,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function submit(): void
    {
        if ($this->status !== 'draft') {
            throw new \RuntimeException('Cannot submit a plan that is not in draft status');
        }

        $this->status = 'submitted';
        $this->submittedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function approve(string $approvedBy): void
    {
        if ($this->status !== 'submitted') {
            throw new \RuntimeException('Cannot approve a plan that is not submitted');
        }

        $this->status = 'approved';
        $this->approvedBy = $approvedBy;
        $this->approvedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function reject(): void
    {
        if ($this->status !== 'submitted') {
            throw new \RuntimeException('Cannot reject a plan that is not submitted');
        }

        $this->status = 'rejected';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): SemesterPlanId
    {
        return $this->id;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    public function semesterId(): SemesterId
    {
        return $this->semesterId;
    }

    public function plannedCourses(): array
    {
        return $this->plannedCourses;
    }

    public function totalCredits(): int
    {
        return $this->totalCredits;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function submittedAt(): ?DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function approvedBy(): ?string
    {
        return $this->approvedBy;
    }

    public function approvedAt(): ?DateTimeImmutable
    {
        return $this->approvedAt;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
