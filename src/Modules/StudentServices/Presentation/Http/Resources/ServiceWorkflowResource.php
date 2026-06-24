<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\ServiceWorkflowDto;

final class ServiceWorkflowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var ServiceWorkflowDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'service_category_id' => $dto->serviceCategoryId,
            'name' => $dto->name,
            'status' => $dto->status,
        ];
    }
}
