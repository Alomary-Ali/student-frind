<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\WorkflowStatus;
use Ramsey\Uuid\Uuid;

final class ServiceWorkflow
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly string $id,
        private readonly string $serviceCategoryId,
        private string $name,
        private WorkflowStatus $status,
        private array $steps,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        string $serviceCategoryId,
        string $name,
        array $steps = [],
    ): self {
        $now = new DateTimeImmutable;

        return new self(
            Uuid::uuid4()->toString(),
            $serviceCategoryId,
            $name,
            WorkflowStatus::ACTIVE,
            $steps,
            $now,
            $now,
        );
    }

    public static function reconstitute(
        string $id,
        string $serviceCategoryId,
        string $name,
        WorkflowStatus $status,
        array $steps,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $serviceCategoryId,
            $name,
            $status,
            $steps,
            $createdAt,
            $updatedAt,
        );
    }

    public function activate(): void
    {
        $this->status = WorkflowStatus::ACTIVE;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function deactivate(): void
    {
        $this->status = WorkflowStatus::INACTIVE;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function addStep(array $stepConfig): void
    {
        $this->steps[] = $stepConfig;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function serviceCategoryId(): string
    {
        return $this->serviceCategoryId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function status(): WorkflowStatus
    {
        return $this->status;
    }

    public function steps(): array
    {
        return $this->steps;
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
