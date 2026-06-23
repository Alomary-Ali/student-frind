<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Skills\Application\DTOs\SkillProfileDto;

/**
 * @property-read SkillProfileDto $resource
 */
final class SkillProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'student_id' => $this->resource->studentId,
            'skills' => SkillResource::collection($this->resource->skills),
            'certifications' => CertificationResource::collection($this->resource->certifications),
        ];
    }
}
