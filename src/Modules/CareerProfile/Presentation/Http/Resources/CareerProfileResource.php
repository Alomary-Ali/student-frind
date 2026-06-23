<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;

/**
 * @property-read CareerProfileDto $resource
 */
final class CareerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'student_id' => $this->resource->studentId,
            'major' => $this->resource->major,
            'summary' => $this->resource->summary,
            'interests' => $this->resource->interests,
            'languages' => $this->resource->languages,
            'portfolio_items' => PortfolioItemResource::collection($this->resource->portfolioItems),
            'experiences' => ExperienceResource::collection($this->resource->experiences),
            'resumes' => ResumeResource::collection($this->resource->resumes),
            'career_goals' => CareerGoalResource::collection($this->resource->careerGoals),
        ];
    }
}
