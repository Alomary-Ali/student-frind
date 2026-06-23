<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\AssistantMessageDto;

final class AssistantMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var AssistantMessageDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'conversation_id' => $dto->conversationId,
            'role' => $dto->role,
            'content' => $dto->content,
            'created_at' => $dto->createdAt,
        ];
    }
}
