<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\ServiceRequestDto;

final class ServiceRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var ServiceRequestDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'ref_number' => $dto->refNumber,
            'category_id' => $dto->categoryId,
            'student_id' => $dto->studentId,
            'status' => $dto->status,
            'priority' => $dto->priority,
            'notes' => $dto->notes,
            'admin_notes' => $dto->adminNotes,
            'created_at' => $dto->createdAt,
            'updated_at' => $dto->updatedAt,
        ];
    }
}
