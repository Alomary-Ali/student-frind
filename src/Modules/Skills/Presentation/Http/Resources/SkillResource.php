<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Skills\Application\DTOs\SkillDto;

/**
 * @property-read SkillDto $resource
 */
final class SkillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'skill_profile_id' => $this->resource->skillProfileId,
            'name' => $this->resource->name,
            'category' => $this->resource->category,
            'category_label' => $this->resource->categoryLabel,
            'level' => $this->resource->level,
            'level_label' => $this->resource->levelLabel,
            'level_weight' => $this->resource->levelWeight,
            'years_of_experience' => $this->resource->yearsOfExperience,
            'last_used' => $this->resource->lastUsed,
        ];
    }
}
