<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\CareerGoalDto;

/**
 * @property-read CareerGoalDto $resource
 */
final class CareerGoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'career_profile_id' => $this->resource->careerProfileId,
            'title' => $this->resource->title,
            'target_date' => $this->resource->targetDate,
            'status' => $this->resource->status,
            'progress' => $this->resource->progress,
        ];
    }
}
