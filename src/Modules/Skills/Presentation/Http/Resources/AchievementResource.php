<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Skills\Application\DTOs\AchievementDto;

/**
 * @property-read AchievementDto $resource
 */
final class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'student_id' => $this->resource->studentId,
            'type' => $this->resource->type,
            'type_label' => $this->resource->typeLabel,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'badge_url' => $this->resource->badgeUrl,
            'unlocked_at' => $this->resource->unlockedAt,
        ];
    }
}
