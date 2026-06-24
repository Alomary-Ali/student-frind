<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\FaqDto;

final class FaqResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var FaqDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'category_id' => $dto->categoryId,
            'question' => $dto->question,
            'answer' => $dto->answer,
            'sort_order' => $dto->sortOrder,
            'is_active' => $dto->isActive,
        ];
    }
}
