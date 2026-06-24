<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\ServiceCategoryDto;

final class ServiceCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var ServiceCategoryDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'type' => $dto->type,
            'description' => $dto->description,
            'is_active' => $dto->isActive,
            'sort_order' => $dto->sortOrder,
        ];
    }
}
