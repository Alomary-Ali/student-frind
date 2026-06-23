<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\ExperienceDto;

/**
 * @property-read ExperienceDto $resource
 */
final class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'career_profile_id' => $this->resource->careerProfileId,
            'company' => $this->resource->company,
            'position' => $this->resource->position,
            'description' => $this->resource->description,
            'start_date' => $this->resource->startDate,
            'end_date' => $this->resource->endDate,
            'is_current' => $this->resource->isCurrent,
        ];
    }
}
