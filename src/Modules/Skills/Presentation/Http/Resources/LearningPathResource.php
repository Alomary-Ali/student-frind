<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Skills\Application\DTOs\LearningPathDto;

/**
 * @property-read LearningPathDto $resource
 */
final class LearningPathResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'student_id' => $this->resource->studentId,
            'title' => $this->resource->title,
            'target_role' => $this->resource->targetRole,
            'steps' => $this->resource->steps,
            'progress' => $this->resource->progress,
            'estimated_completion_date' => $this->resource->estimatedCompletionDate,
        ];
    }
}
