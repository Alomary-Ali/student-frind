<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class WorkflowStepDto
{
    public function __construct(
        public string $id,
        public string $workflowId,
        public string $name,
        public string $type,
        public int $order,
        public ?string $assigneeRole,
        public string $status,
    ) {}
}
