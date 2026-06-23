<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'progress_percentage' => $this->progress_percentage,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
