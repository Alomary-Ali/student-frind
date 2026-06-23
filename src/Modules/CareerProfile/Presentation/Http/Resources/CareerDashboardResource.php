<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\CareerDashboardDto;

/**
 * @property-read CareerDashboardDto $resource
 */
final class CareerDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'profile' => $this->resource->profile ? new CareerProfileResource($this->resource->profile) : null,
            'career_score' => $this->resource->careerScore,
            'linked_in_score' => $this->resource->linkedInScore,
            'portfolio_items' => PortfolioItemResource::collection($this->resource->portfolioItems),
            'experiences' => ExperienceResource::collection($this->resource->experiences),
            'career_goals' => CareerGoalResource::collection($this->resource->careerGoals),
            'skill_count' => $this->resource->skillCount,
            'certification_count' => $this->resource->certificationCount,
        ];
    }
}
