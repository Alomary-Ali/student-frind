<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SkillGapReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'role' => $this->resource['role'],
            'current_skills' => $this->resource['current_skills'],
            'missing_skills' => $this->resource['missing_skills'],
            'matching_percentage' => $this->resource['matching_percentage'],
        ];
    }
}
