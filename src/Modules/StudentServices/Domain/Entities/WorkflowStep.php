<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use Modules\StudentServices\Domain\Enums\WorkflowStatus;
use Modules\StudentServices\Domain\Enums\WorkflowStepType;
use Modules\StudentServices\Domain\ValueObjects\WorkflowStepId;

final class WorkflowStep
{
    private function __construct(
        private readonly WorkflowStepId $id,
        private readonly string $workflowId,
        private string $name,
        private readonly WorkflowStepType $type,
        private int $order,
        private array $config,
        private ?string $assigneeRole,
        private WorkflowStatus $status,
    ) {}

    public static function create(
        WorkflowStepId $id,
        string $workflowId,
        string $name,
        WorkflowStepType $type,
        int $order,
        array $config = [],
        ?string $assigneeRole = null,
    ): self {
        return new self(
            $id,
            $workflowId,
            $name,
            $type,
            $order,
            $config,
            $assigneeRole,
            WorkflowStatus::ACTIVE,
        );
    }

    public static function reconstitute(
        WorkflowStepId $id,
        string $workflowId,
        string $name,
        WorkflowStepType $type,
        int $order,
        array $config,
        ?string $assigneeRole,
        WorkflowStatus $status,
    ): self {
        return new self(
            $id,
            $workflowId,
            $name,
            $type,
            $order,
            $config,
            $assigneeRole,
            $status,
        );
    }

    public function complete(): void
    {
        $this->status = WorkflowStatus::INACTIVE;
    }

    public function skip(): void
    {
        $this->status = WorkflowStatus::ARCHIVED;
    }

    public function id(): WorkflowStepId
    {
        return $this->id;
    }

    public function workflowId(): string
    {
        return $this->workflowId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): WorkflowStepType
    {
        return $this->type;
    }

    public function order(): int
    {
        return $this->order;
    }

    public function config(): array
    {
        return $this->config;
    }

    public function assigneeRole(): ?string
    {
        return $this->assigneeRole;
    }

    public function status(): WorkflowStatus
    {
        return $this->status;
    }
}
