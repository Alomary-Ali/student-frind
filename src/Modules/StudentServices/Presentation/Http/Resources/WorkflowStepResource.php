<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\WorkflowStepDto;

final class WorkflowStepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var WorkflowStepDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'workflow_id' => $dto->workflowId,
            'name' => $dto->name,
            'type' => $dto->type,
            'order' => $dto->order,
            'assignee_role' => $dto->assigneeRole,
            'status' => $dto->status,
        ];
    }
}
