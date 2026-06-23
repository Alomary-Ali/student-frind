<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Career\Application\DTOs\ComprehensiveDashboardDto;

final class ComprehensiveDashboardResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ComprehensiveDashboardDto $dto */
        $dto = $this->resource;

        return [
            'profile' => $dto->profile,
            'skill_profile' => $dto->skillProfile,
            'opportunities' => $dto->opportunities,
            'interviews' => $dto->interviews,
            'career_paths' => $dto->careerPaths,
            'readiness_score' => $dto->readinessScore,
            'readiness_breakdown' => $dto->readinessBreakdown,
            'recent_activity' => $dto->recentActivity,
        ];
    }
}
