<?php

declare(strict_types=1);

namespace Modules\Notifications\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Notifications\Application\DTOs\NotificationDto;

final class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var NotificationDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'student_id' => $dto->studentId,
            'type' => $dto->type,
            'title' => $dto->title,
            'message' => $dto->message,
            'channel' => $dto->channel,
            'link' => $dto->link,
            'is_read' => $dto->isRead,
            'created_at' => $dto->createdAt,
        ];
    }
}
