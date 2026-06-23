<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\ResumeDto;

/**
 * @property-read ResumeDto $resource
 */
final class ResumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'career_profile_id' => $this->resource->careerProfileId,
            'template' => $this->resource->template,
            'content' => $this->resource->content,
            'generated_at' => $this->resource->generatedAt,
        ];
    }
}
