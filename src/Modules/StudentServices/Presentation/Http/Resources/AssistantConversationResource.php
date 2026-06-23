<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\AssistantConversationDto;

final class AssistantConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var AssistantConversationDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'student_id' => $dto->studentId,
            'title' => $dto->title,
            'status' => $dto->status,
            'last_activity_at' => $dto->lastActivityAt,
            'created_at' => $dto->createdAt,
        ];
    }
}
