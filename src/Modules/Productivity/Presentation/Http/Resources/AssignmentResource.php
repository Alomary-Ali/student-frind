<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_at' => $this->assigned_at,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'grade' => $this->grade,
            'submission_url' => $this->submission_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
