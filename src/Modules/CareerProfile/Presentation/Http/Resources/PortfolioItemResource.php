<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\PortfolioItemDto;

/**
 * @property-read PortfolioItemDto $resource
 */
final class PortfolioItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'career_profile_id' => $this->resource->careerProfileId,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'project_url' => $this->resource->projectUrl,
            'github_url' => $this->resource->githubUrl,
            'start_date' => $this->resource->startDate,
            'end_date' => $this->resource->endDate,
            'technologies' => $this->resource->technologies,
        ];
    }
}
