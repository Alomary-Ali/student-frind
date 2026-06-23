<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Career\Application\DTOs\InterviewDto;

final class InterviewResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var InterviewDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'student_id' => $dto->studentId,
            'type' => $dto->type,
            'status' => $dto->status,
            'scheduled_at' => $dto->scheduledAt,
            'questions' => $dto->questions,
            'score' => $dto->score,
            'feedback' => $dto->feedback,
        ];
    }
}
