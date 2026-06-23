<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'exam_type' => $this->exam_type,
            'exam_date' => $this->exam_date,
            'location' => $this->location,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
