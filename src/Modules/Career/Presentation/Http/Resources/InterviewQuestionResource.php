<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Career\Application\DTOs\InterviewQuestionDto;

final class InterviewQuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var InterviewQuestionDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'interview_id' => $dto->interviewId,
            'question' => $dto->question,
            'category' => $dto->category,
            'order' => $dto->order,
        ];
    }
}
